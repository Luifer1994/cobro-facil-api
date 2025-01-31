<?php

namespace App\Http\Modules\Loans\Services;

use App\Http\Modules\Loans\Repositories\LoanRepository;
use App\Http\Bases\BaseService;
use App\Http\Modules\Installments\Repositories\InstallmentRepository;
use App\Support\Result;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanService extends BaseService
{
    public function __construct(
        protected LoanRepository $loanRepository,
        protected InstallmentRepository $installmentRepository
    ) {}

    /**
     * Crea un préstamo
     *
     * @param array $validatedData Datos del préstamo
     * @return Result
     */
    public function create(array $validatedData): Result
    {
        DB::beginTransaction();
        try {
            $monthlyRate = $validatedData['interest_rate'] / 100;
            $frequency = $validatedData['payment_frequency'];
            $installmentsCount = $validatedData['installments_count'];

            // Calcular tasa periódica según frecuencia
            $periodicRate = match ($frequency) {
                'daily' => $monthlyRate / 30,
                'weekly' => $monthlyRate / 4,
                'biweekly' => $monthlyRate / 2,
                'monthly' => $monthlyRate,
                default => 0
            };

            // Calcular interés total según tipo
            $totalInterest = match ($validatedData['interest_type']) {
                'fixed' => $validatedData['amount'] * $monthlyRate * ($installmentsCount / $this->getDivisor($frequency)),
                'reducing' => $validatedData['amount'] * $periodicRate * ($installmentsCount + 1) / 2,
                default => 0
            };

            $totalToPay = $validatedData['amount'] + $totalInterest;

            $loanData = array_merge($validatedData, [
                'user_id' => Auth::id(),
                'outstanding_balance' => round($totalToPay, 2),
                'start_date' => Carbon::now()->format('Y-m-d'),
            ]);

            $loan = $this->loanRepository->store($loanData);

            $dueDates = $this->calculateDueDates(
                Carbon::parse($loanData['start_date']),
                $loanData['installments_count'],
                $loanData['payment_frequency']
            );

            $installments = $this->generateInstallments(
                $loanData['amount'],
                $totalToPay,
                $monthlyRate,
                $loanData['interest_type'],
                $loanData['payment_frequency'],
                $dueDates,
                $loan->id,
                $loan->user_id
            );

            $this->installmentRepository->insert($installments);

            DB::commit();
            return Result::success(message: 'Préstamo creado exitosamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return Result::failure(
                error: 'Error al crear préstamo',
                message: $th->getMessage()
            );
        }
    }

    /**
     * Calcula el número de meses según la frecuencia del préstamo.
     * 
     * @param string Carbon $startDate
     * @param int $installmentsCount
     * @param string $paymentFrequency
     * @return array
     */
    private function calculateDueDates(
        Carbon $startDate,
        int $installmentsCount,
        string $paymentFrequency
    ): array {
        $currentDate = $startDate->copy();

        // Corregir la definición del intervalo
        $interval = match ($paymentFrequency) {
            'daily' => ['method' => 'addDay', 'params' => []],
            'weekly' => ['method' => 'addWeek', 'params' => []],
            'biweekly' => ['method' => 'addWeeks', 'params' => [2]],
            'monthly' => ['method' => 'addMonth', 'params' => []],
            default => throw new \InvalidArgumentException("Frecuencia inválida")
        };

        $dueDates = [];
        for ($i = 0; $i < $installmentsCount; $i++) {
            $currentDate->{$interval['method']}(...$interval['params']);
            $dueDates[] = $currentDate->format('Y-m-d');
        }

        return $dueDates;
    }

    /**
     * Genera las facturas de préstamo según los datos del préstamo.
     * 
     * @param float $principal
     * @param float $totalToPay
     * @param float $monthlyRate
     * @param string $interestType
     * @param string $paymentFrequency
     * @param array $dueDates
     * @param int $loanId
     * @param int $userId
     * @return array
     */
    private function generateInstallments(
        float $principal,
        float $totalToPay,
        float $monthlyRate,
        string $interestType,
        string $paymentFrequency,
        array $dueDates,
        int $loanId,
        int $userId
    ): array {
        $capitalBase = $principal / count($dueDates);
        $installments = [];
        $periodicRate = $this->getPeriodicRate($monthlyRate, $paymentFrequency);

        foreach ($dueDates as $i => $dueDate) {
            $remainingPrincipal = $principal - ($capitalBase * $i);

            $interest = match ($interestType) {
                'fixed' => ($totalToPay - $principal) / count($dueDates),
                'reducing' => $remainingPrincipal * $periodicRate,
                default => throw new \InvalidArgumentException("Tipo de interés inválido")
            };

            $installments[] = [
                'due_date' => $dueDate,
                'expected_amount' => round($capitalBase + $interest, 2),
                'capital_balance' => round($capitalBase, 2),
                'interest_balance' => round($interest, 2),
                'loan_id' => $loanId,
                'user_id' => $userId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return $installments;
    }

    /**
     * Calcula el tasa periódica según la frecuencia del préstamo.
     * 
     * @param float $monthlyRate
     * @param string $frequency
     * @return float
     */
    private function getPeriodicRate(float $monthlyRate, string $frequency): float
    {
        return match ($frequency) {
            'daily' => $monthlyRate / 30,     // 10% mensual = 0.333% diario
            'weekly' => $monthlyRate / 4,     // 10% mensual = 2.5% semanal
            'biweekly' => $monthlyRate / 2,   // 10% mensual = 5% quincenal
            'monthly' => $monthlyRate,
            default => throw new \InvalidArgumentException("Frecuencia no soportada")
        };
    }

    /**
     * Calcula el divisor según la frecuencia del préstamo.
     * 
     * @param string $frequency
     * @return int
     */
    private function getDivisor(string $frequency): int
    {
        return match ($frequency) {
            'daily' => 30,
            'weekly' => 4,
            'biweekly' => 2,
            'monthly' => 1,
            default => 1
        };
    }
}

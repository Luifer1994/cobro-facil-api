<?php

namespace App\Http\Modules\DocumentTypes\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\DocumentTypes\Repositories\DocumentTypeRepository;
use App\Support\Result;
use Illuminate\Http\JsonResponse;

class DocumentTypeController extends BaseController
{

    public function __construct(
        protected DocumentTypeRepository $documentTypeRepository
    ) {}


    /**
     *  List all document types.
     * 
     * @return JsonResponse
     */
    public function listAll(): JsonResponse
    {
        try {
            return $this->response(Result::success(message: 'Recursos obtenidos correctamente', value: $this->documentTypeRepository->listAll()));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener los recursos', message: $th->getMessage()));
        }
    }
}
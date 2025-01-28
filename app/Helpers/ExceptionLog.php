<?php

if (!function_exists('custom_log')) {
    /**
     * Registro de log de excepciones simplificado.
     *
     * @param Throwable $exception : Excepción generada.
     * @param string|null $className : Nombre de la clase personalizado (opcional).
     * @param string $process : Nombre del proceso relacionado con el log (opcional).
     * @return void
     */
    function custom_log(\Throwable $exception, ?string $className = null, string $process = "Proceso fallido."): void
    {
        // Si no se proporciona un nombre de clase, obtenerlo desde el trace
        if ($className === null) {
            $trace = $exception->getTrace();
            $class = 'Global'; // Valor predeterminado si no se encuentra una clase

            foreach ($trace as $item) {
                if (isset($item['class'])) {
                    $class = $item['class'];
                    break;
                }
            }

            // Convertir el nombre de la clase a un formato válido para nombres de archivo
            $className = \Illuminate\Support\Str::snake(class_basename($class));
        } else {
            // Si se proporciona un nombre de clase (texto o __CLASS__), normalizarlo
            $className = \Illuminate\Support\Str::snake(class_basename($className));
        }

        // Crear la carpeta de logs si no existe
        $logPath = storage_path('logs/exceptions/' . $className);
        if (!file_exists($logPath)) {
            mkdir($logPath, 0777, true);
        }

        // Registrar el error en el archivo de log correspondiente
        $logFile = $logPath . '/log_' . $className . '_' . date('Ymd') . '.log';
        $logMessage = sprintf(
            "[%s] Error en %s:\nLine: %s\nMessage: %s\nProcess: %s\nTrace: %s\n\n",
            date('Y-m-d H:i:s'),
            $className,
            $exception->getLine(),
            $exception->getMessage(),
            $process,
            $exception->getTraceAsString()
        );

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
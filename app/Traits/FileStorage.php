<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait FileStorage
{
    /**
     * Upload file in storage folder.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    public function uploadFile(UploadedFile $file, string $folder): string
    {
        $disk = 'public';
        // Get file name
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        // Store file and return path relative
        $filePath = $file->storeAs($folder, $fileName, $disk);
        return $filePath;
    }

    /**
     * Get file from storage folder.
     *
     * @param string $filePath
     * @return string|null
     */
    public function getFile(string $filePath): ?string
    {
        $disk = 'public';
        if (Storage::disk($disk)->exists($filePath)) return asset('storage/' . $filePath);
        return null;
    }

    /**
     * Delete file from storage folder.
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteFile(string $filePath): bool
    {
        $disk = 'public';
        try {
            if (Storage::disk($disk)->exists($filePath))  return Storage::disk($disk)->delete($filePath);
            else throw new Exception("Archivo no encontrado.");
        } catch (Exception $e) {
            Log::error("Error al eliminar archivo: " . $e->getMessage());
            return false;
        }
    }
}

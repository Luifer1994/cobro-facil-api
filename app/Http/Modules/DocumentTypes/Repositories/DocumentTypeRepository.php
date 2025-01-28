<?php

namespace App\Http\Modules\DocumentTypes\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\DocumentTypes\Models\DocumentType;
use Illuminate\Database\Eloquent\Collection;

class DocumentTypeRepository extends BaseRepository
{
    public function __construct(protected DocumentType $documentTypeModel)
    {
        parent::__construct($documentTypeModel);
    }

    /**
     * List all document types.
     * 
     * @return Collection
     */
    public function listAll(): Collection
    {
        return $this->documentTypeModel->select('id', 'code', 'name')->get();
    }
}

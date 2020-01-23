<?php

namespace Backend\Domain\Services;


use Backend\Domain\Entities\DocumentEntity;
use Backend\Domain\Services\Exceptions\AlreadyPublishedException;

interface DocumentServiceInterface
{

    /**
     * Create new document
     * @return DocumentEntity
     */
    public function create(): DocumentEntity;

    /**
     * Change document
     * @param string $documentId
     * @param object $payload
     * @throws AlreadyPublishedException
     * @return DocumentEntity
     */
    public function change(string $documentId, object $payload): DocumentEntity;

    /**
     * Publish document
     * @param string $documentId
     * @return DocumentEntity
     */
    public function publish(string $documentId): DocumentEntity;

}
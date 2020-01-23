<?php

namespace Backend\Services;


use Backend\Domain\Entities\DocumentEntity;
use Backend\Domain\Repositories\DocumentRepositoryInterface;
use Backend\Domain\Repositories\Exceptions\CanNotCreateException;
use Backend\Domain\Repositories\Exceptions\CanNotUpdateException;
use Backend\Domain\Repositories\Exceptions\NotFoundException;
use Backend\Domain\Services\DocumentServiceInterface;
use Backend\Domain\Services\Exceptions\AlreadyPublishedException;
use Backend\Models\Document;
use Backend\Models\Enums\DocumentStatus;

class DocumentService extends Service implements DocumentServiceInterface
{
    protected $repository;

    public function __construct(DocumentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return DocumentEntity
     * @throws CanNotCreateException
     * @throws \Phalcon\Security\Exception
     */
    public function create(): DocumentEntity
    {

        $document = new Document();

        $document->setId($this->security->getRandom()->uuid());
        $document->setPayload(new \stdClass());
        $document->setStatus(DocumentStatus::DRAFT()->getValue());

        $this->repository->create($document);

        return $document;

    }

    /**
     * @param string $documentId
     * @param object $payload
     * @return DocumentEntity
     * @throws AlreadyPublishedException
     * @throws CanNotUpdateException
     * @throws NotFoundException
     */
    public function change(string $documentId, object $payload): DocumentEntity
    {

        $document = $this->repository->findById($documentId);

        if (!$document->getStatus()->equals(DocumentStatus::DRAFT())) {
            throw new AlreadyPublishedException();
        }

        $document->setPayload($this->mergePayload($document->getPayload(), $payload));

        $this->repository->update($document);

        return $document;
    }

    /**
     * @param string $documentId
     * @return DocumentEntity
     * @throws CanNotUpdateException
     * @throws NotFoundException
     */
    public function publish(string $documentId): DocumentEntity
    {

        $document = $this->repository->findById($documentId);

        if (!$document->getStatus()->equals(DocumentStatus::DRAFT())) {
            return $document;
        }

        $document->setStatus(DocumentStatus::PUBLISHED()->getValue());

        $this->repository->update($document);

        return $document;
    }

    protected function mergePayload(object $payload, object $newPayload): object
    {
        $merged = array_replace_recursive((array)$payload, (array)$newPayload);

        $merged = $this->clearNulls($merged);

        return (object)$merged;
    }

    protected function clearNulls($array): array
    {
        foreach ($array as $key => &$value) {

            if (is_array($value)) {

                $value = $this->clearNulls($value);

            } else if ($value === null) {

                unset($array[$key]);

            }
        }

        return $array;
    }
}
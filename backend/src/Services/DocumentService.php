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
use Phalcon\Helper\Json;

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
        return $this->objectReplaceRecursive($payload, $newPayload);
    }

    protected function objectReplaceRecursive(object $object1, object $object2): object
    {

        foreach ($object2 as $key => $item) {

            $value = null;

            if ($item === null) {
                unset($object1->{$key});
                continue;
            }

            if (is_object($item)) {

                $value = $this->objectReplaceRecursive(
                    isset($object1->{$key}) && is_object($object1->{$key}) ? $object1->{$key} : new \stdClass(),
                    $item
                );

            } else {

                $value = $item;

            }

            if ($value === null && property_exists($object1, $key)) {

                unset($object1->{$key});

            } else {

                $object1->{$key} = $value;

            }

        }

        return $object1;
    }
}
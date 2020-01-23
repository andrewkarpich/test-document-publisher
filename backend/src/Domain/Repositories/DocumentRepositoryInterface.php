<?php

namespace Backend\Domain\Repositories;


use Backend\Domain\Entities\DocumentEntity;
use Backend\Domain\Repositories\Exceptions\CanNotCreateException;
use Backend\Domain\Repositories\Exceptions\CanNotUpdateException;
use Backend\Domain\Repositories\Exceptions\NotFoundException;

interface DocumentRepositoryInterface
{

    /**
     * Create document
     * @param DocumentEntity $entity
     * @throws CanNotCreateException
     */
    public function create(DocumentEntity $entity): void;

    /**
     * Update document
     * @param DocumentEntity $entity
     * @throws CanNotUpdateException
     */
    public function update(DocumentEntity $entity): void;

    /**
     * Find document by id
     * @param string $id
     * @throws NotFoundException
     * @return DocumentEntity
     */
    public function findById(string $id): DocumentEntity;

    /**
     * Get documents list
     * @param int $page
     * @param int $count
     * @return array
     */
    public function getList(int $page, int $count): array;

    /**
     * Get count all documents
     * @return int
     */
    public function countAll(): int;

}
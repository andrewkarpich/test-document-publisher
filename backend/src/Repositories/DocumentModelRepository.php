<?php

namespace Backend\Repositories;


use Backend\Domain\Entities\DocumentEntity;
use Backend\Domain\Repositories\DocumentRepositoryInterface;
use Backend\Domain\Repositories\Exceptions\CanNotCreateException;
use Backend\Domain\Repositories\Exceptions\CanNotUpdateException;
use Backend\Domain\Repositories\Exceptions\NotFoundException;
use Backend\Models\Document;
use Phalcon\Mvc\Model\Resultset\Simple;

class DocumentModelRepository extends Repository implements DocumentRepositoryInterface
{

    public function create(DocumentEntity $entity): void
    {

        if ($entity instanceof Document) {

            $this->db->begin();

            if (!$entity->create()) {

                $this->db->rollback();

                throw new CanNotCreateException();
            }

            $this->db->commit();

        }

    }

    public function update(DocumentEntity $entity): void
    {

        if ($entity instanceof Document) {

            $this->db->begin();

            if (!$entity->update()) {

                $this->db->rollback();

                throw new CanNotUpdateException();
            }

            $this->db->commit();

        }

    }

    public function findById(string $id): DocumentEntity
    {

        $document = Document::findFirst([
            'id = :id:',
            'bind' => [
                'id' => $id
            ]
        ]);

        if (!$document) throw new NotFoundException();

        return $document;

    }

    public function getList(int $page, int $count): array
    {

        /**
         * @var Simple $result
         */
        $result = $this->modelsManager->createBuilder()
            ->from(['d' => Document::class])
            ->orderBy('create_at DESC')
            ->limit($count, $count * ($page - 1))
            ->getQuery()
            ->execute();

        return $result->filter(function ($document) {
            return $document;
        });
    }

    public function countAll(): int
    {
        return Document::count();
    }
}
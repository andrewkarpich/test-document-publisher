<?php

namespace Backend\Transformers;


use Backend\Domain\Entities\DocumentEntity;
use League\Fractal\TransformerAbstract;

class DocumentTransformer extends TransformerAbstract
{
    public function transform(DocumentEntity $entity): array
    {

        return [
            'id'       => $entity->getId(),
            'status'   => $entity->getStatus()->getValue(),
            'payload'  => $entity->getPayload(),
            'createAt' => $entity->getCreateAt()->toIso8601String(),
            'modifyAt' => $entity->getModifyAt()->toIso8601String()
        ];

    }
}
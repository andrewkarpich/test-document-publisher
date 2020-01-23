<?php

namespace Backend\Models;


use Backend\Domain\Entities\DocumentEntity;
use Backend\Models\Behaviors\BeforeAfterSave;
use Backend\Models\Behaviors\CarbonTimestampable;
use Backend\Models\Enums\DocumentStatus;
use Carbon\Carbon;
use MyCLabs\Enum\Enum;
use Phalcon\Mvc\Model;

class Document extends Model implements DocumentEntity
{

    public function initialize()
    {
        $this->addBehavior(new CarbonTimestampable([
            'create' => ['create_at', 'modify_at'],
            'update' => 'modify_at'
        ]));

        $this->addBehavior(new BeforeAfterSave([
            'after'  => [
                'payload' => function ($value) {
                    return is_string($value) ? json_decode($value) : $value;
                },
                'status'  => function ($value) {
                    return is_string($value) ? new DocumentStatus($value) : $value;
                }
            ],
            'before' => [
                'payload' => function ($value) {
                    return is_object($value) ? json_encode($value) : $value;
                },
                'status'  => function ($value) {
                    /**
                     * @var DocumentStatus $value
                     */
                    return is_object($value) ? $value->getValue() : $value;
                }
            ]
        ]));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): DocumentEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getStatus(): Enum
    {
        return $this->status;
    }

    public function setStatus($status): DocumentEntity
    {
        $this->status = $status;
        return $this;
    }

    public function getPayload(): object
    {
        return $this->payload;
    }

    public function setPayload($payload): DocumentEntity
    {
        $this->payload = $payload;
        return $this;
    }

    public function getModifyAt(): Carbon
    {
        return $this->modify_at;
    }

    public function setModifyAt($modify_at): DocumentEntity
    {
        $this->modify_at = $modify_at;
        return $this;
    }

    public function getCreateAt(): Carbon
    {
        return $this->create_at;
    }

    public function setCreateAt($create_at): DocumentEntity
    {
        $this->create_at = $create_at;
        return $this;
    }
}
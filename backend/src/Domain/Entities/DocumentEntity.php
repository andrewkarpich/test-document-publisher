<?php

namespace Backend\Domain\Entities;

use Carbon\Carbon;
use MyCLabs\Enum\Enum;

interface DocumentEntity
{
    public function getId(): string;

    public function setId(string $id): DocumentEntity;

    public function getStatus(): Enum;

    public function setStatus($status): DocumentEntity;

    public function getPayload(): object;

    public function setPayload($payload): DocumentEntity;

    public function getModifyAt(): Carbon;

    public function setModifyAt($modify_at): DocumentEntity;

    public function getCreateAt(): Carbon;

    public function setCreateAt($create_at): DocumentEntity;
}
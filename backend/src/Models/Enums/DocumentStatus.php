<?php

namespace Backend\Models\Enums;



use MyCLabs\Enum\Enum;

/**
 * @method static DocumentStatus DRAFT()
 * @method static DocumentStatus PUBLISHED()
 */
class DocumentStatus extends Enum
{

    private const DRAFT = 'draft';
    private const PUBLISHED = 'published';

}
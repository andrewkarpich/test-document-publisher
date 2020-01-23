<?php

use Backend\Controllers\DocumentController;
use Phalcon\Mvc\Micro\Collection;

$documentCollection = new Collection();

$documentCollection
    ->setPrefix('/api/v1/document')
    ->setHandler(DocumentController::class, true);

$documentCollection
    ->mapVia('/', 'createAction', ['POST'])
    ->mapVia('/{id}', 'getAction', ['GET'])
    ->mapVia('/', 'getListAction', ['GET'])
    ->mapVia('/{id}', 'editAction', ['PATCH'])
    ->mapVia('/{id}/publish', 'publishAction', ['POST']);


return [
    $documentCollection
];
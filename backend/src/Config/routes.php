<?php

use Backend\Controllers\DocumentController;
use Phalcon\Mvc\Micro\Collection;

$documentCollection = new Collection();

$documentCollection
    ->setPrefix('/api/v1/document')
    ->setHandler(DocumentController::class, true);

$documentCollection
    ->mapVia('/', 'indexAction', ['GET', 'POST']);


return [
    $documentCollection
];
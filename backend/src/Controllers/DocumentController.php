<?php

namespace Backend\Controllers;

use Backend\Application\Responses\Fractal\BasePaginator;
use Backend\Application\Responses\JsonResponse;
use Backend\Domain\Repositories\Exceptions\CanNotCreateException;
use Backend\Domain\Repositories\Exceptions\CanNotUpdateException;
use Backend\Domain\Repositories\Exceptions\NotFoundException;
use Backend\Domain\Services\Exceptions\AlreadyPublishedException;
use Backend\Repositories\DocumentModelRepository;
use Backend\Services\DocumentService;
use Backend\Transformers\DocumentTransformer;
use Phalcon\Mvc\Controller;

class DocumentController extends Controller
{

    /**
     * @return JsonResponse
     * @throws CanNotCreateException
     * @throws \Phalcon\Security\Exception
     */
    public function createAction(): JsonResponse
    {

        $service = new DocumentService(new DocumentModelRepository());

        $document = $service->create();

        return (new JsonResponse())->withTransform($document, new DocumentTransformer(), 'document');

    }

    public function getAction(string $id): JsonResponse
    {
        try {

            $document = (new DocumentModelRepository())->findById($id);

        } catch (NotFoundException $e) {

            return new JsonResponse(null, 404, $e->getMessage());

        }

        return (new JsonResponse())->withTransform($document, new DocumentTransformer(), 'document');
    }

    public function getListAction(): JsonResponse
    {
        $page = $this->request->get('page', 'int', 1);
        $count = $this->request->get('perPage', 'int', 20);

        $repository = new DocumentModelRepository();

        $items = $repository->getList($page, $count);
        $countAll = $repository->countAll();

        return (new JsonResponse())->withTransformCollection(
            $items,
            new DocumentTransformer(),
            'document',
            null,
            new BasePaginator($page, $count, $countAll)
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws CanNotUpdateException
     */
    public function editAction(string $id): JsonResponse
    {
        $changes = $this->request->getJsonRawBody();

        if (!isset($changes->document, $changes->document->payload)) {
            return new JsonResponse(null, 400, 'Invalid request');
        }

        $service = new DocumentService(new DocumentModelRepository());

        try {

            $document = $service->change($id, $changes->document->payload);

        } catch (NotFoundException $e) {

            return new JsonResponse(null, 404, $e->getMessage());

        } catch (AlreadyPublishedException $e) {

            return new JsonResponse(null, 400, $e->getMessage());

        }

        return (new JsonResponse())->withTransform($document, new DocumentTransformer(), 'document');
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws CanNotUpdateException
     */
    public function publishAction(string $id): JsonResponse
    {
        $service = new DocumentService(new DocumentModelRepository());

        try {
            $document = $service->publish($id);
        } catch (NotFoundException $e) {
            return new JsonResponse(null, 404, $e->getMessage());
        }

        return (new JsonResponse())->withTransform($document, new DocumentTransformer(), 'document');
    }
}
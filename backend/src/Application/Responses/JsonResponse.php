<?php

namespace Backend\Application\Responses;


use Backend\Application\Responses\Fractal\BaseSerializer;
use League\Fractal\Manager;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;
use Phalcon\Http\Response;

class JsonResponse extends Response
{

    public function __construct($content = null, $code = 200, $status = 'OK')
    {

        parent::__construct($content, $code, $status);

        $content = $this->getContent();

        if ($content) {

            $this->setJsonContent($this->getContent(), JSON_THROW_ON_ERROR, 512);

        }

    }

    public function withTransform(
        $data = null,
        TransformerAbstract $transformer = null,
        string $resourceKey = null,
        SerializerAbstract $serializer = null): JsonResponse
    {

        $resource = new Item($data, $transformer, $resourceKey);

        $this->transformContent($resource, $serializer);

        return $this;
    }

    public function withTransformCollection(
        $data = null,
        TransformerAbstract $transformer = null,
        string $resourceKey = null,
        SerializerAbstract $serializer = null,
        PaginatorInterface $paginator = null
    ): JsonResponse
    {

        $resource = new Collection($data, $transformer, $resourceKey);

        if ($paginator) {
            $resource->setPaginator($paginator);
        }

        $this->transformContent($resource, $serializer);

        return $this;
    }

    protected function transformContent(ResourceAbstract $resource, SerializerAbstract $serializer = null)
    {

        if (!$serializer) {
            $serializer = new BaseSerializer();
        }

        $manager = new Manager();
        $manager->setSerializer($serializer);

        $this->setContent($manager->createData($resource)->toJson());

    }

}
<?php

namespace Backend\Application\Responses\Fractal;


use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\ArraySerializer;

class BaseSerializer extends ArraySerializer
{

    public function item($resourceKey, array $data)
    {
        return [$resourceKey => $data];
    }

    public function meta(array $meta)
    {
        if (empty($meta)) {
            return [];
        }

        return $meta;
    }

    public function paginator(PaginatorInterface $paginator)
    {
        return [
            'pagination' => [
                'total'   => (int)$paginator->getLastPage(),
                'perPage' => (int)$paginator->getPerPage(),
                'page'    => (int)$paginator->getCurrentPage(),
            ]
        ];
    }

}
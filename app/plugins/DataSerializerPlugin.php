<?php

namespace RealWorld\Plugins;

use League\Fractal\Serializer\DataArraySerializer;

/**
 * Class DataSerializerPlugin
 * @package RealWorld\Plugins
 */
class DataSerializerPlugin extends DataArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        $resourceKey = $this->checkResourceKey($resourceKey);

        return [$resourceKey => $data];
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        $resourceKey = $this->checkResourceKey($resourceKey);

        return [$resourceKey => $data];
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null()
    {
        return ['data' => []];
    }

    /**
     * @param $resourceKey
     * @return string
     */
    protected function checkResourceKey($resourceKey)
    {
        if (!$resourceKey) {
            return 'data';
        }

        return $resourceKey;
    }
}
<?php

namespace RealWorld\Transformers;

/**
 * Class TagTransformer
 * @package RealWorld\Transformers
 */
class TagTransformer extends Transformer
{
    /**
     * @var string
     */
    protected $resourceKey = 'tag';

    /**
     * @param array $data
     * @return array
     */
    public function transform(array $data)
    {
        return $data;
    }
}
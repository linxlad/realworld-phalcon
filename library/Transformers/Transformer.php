<?php

namespace RealWorld\Transformers;

use League\Fractal\TransformerAbstract;
use RealWorld\Models\User;

/**
 * Class ProfileTransformer
 * @package RealWorld\Transformers
 */
class Transformer extends TransformerAbstract
{
    /**
     * @var string
     */
    protected $resourceKey = 'data';

    /**
     * @return string
     */
    public function getResourceKey()
    {
        return $this->resourceKey;
    }
}
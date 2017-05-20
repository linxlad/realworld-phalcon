<?php

namespace RealWorld\Transformers;

use RealWorld\Models\User;

/**
 * Class ProfileTransformer
 * @package RealWorld\Transformers
 */
class ProfileTransformer extends Transformer
{
    /**
     * @var string
     */
    protected $resourceKey = 'profile';

    /**
     * @param User $data
     * @return array
     */
    public function transform(User $data)
    {
        return $data->toArray([
            'username',
            'bio',
            'image',
            'following'
        ]);
    }
}
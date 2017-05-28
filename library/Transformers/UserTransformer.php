<?php

namespace RealWorld\Transformers;

use RealWorld\Models\User;

/**
 * Class UserTransformer
 * @package RealWorld\Transformers
 */
class UserTransformer extends Transformer
{
    /**
     * @var string
     */
    protected $resourceKey = 'user';

    /**
     * @param User $data
     * @return array
     */
    public function transform(User $data)
    {
        return $data->toArray([
            'email',
            'username',
            'bio',
            'image',
            'token',
        ]);
    }
}
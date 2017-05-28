<?php

namespace RealWorld\Transformers;

use Phalcon\Mvc\Model\Row;
use RealWorld\Models\Comments;

/**
 * Class CommentTransformer
 * @package RealWorld\Transformers
 */
class CommentTransformer extends Transformer
{
    /**
     * @var string
     */
    protected $resourceKey = 'comment';

    /**
     * @param Comments|Row $data
     * @return array
     */
    public function transform(Comments $data)
    {
        $author = $data->user->toArray();
        $data = $data->toArray();
        unset($data['userId'], $data['articleId']);
        $data['author'] = [
            'username' => $author['username'],
            'bio' => $author['bio'],
            'image' => $author['image'],
            'following' => $author['following'] ?? false,
        ];

        return $data;
    }
}
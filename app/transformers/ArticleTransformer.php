<?php

namespace RealWorld\Transformers;

use RealWorld\Models\Articles;

/**
 * Class ArticleTransformer
 * @package RealWorld\Transformers
 */
class ArticleTransformer extends Transformer
{
    /**
     * @var string
     */
    protected $resourceKey = 'article';

    /**
     * @param Articles $data
     * @return array
     */
    public function transform(Articles $data)
    {
        $author = $data->user->toArray();
        $data = $data->toArray();
        unset($data['id'], $data['userId']);

        // TODO: Add tagList, favoritedand  favoritesCount.
        $data['tagList'] = [];
        $data['favorited'] = false;
        $data['favoritesCount'] = false;

        $data['author'] = [
            'username' => $author['username'],
            'bio' => $author['bio'],
            'image' => $author['image'],
            'following' => $author['following'] ?? false,
        ];

        return $data;
    }
}
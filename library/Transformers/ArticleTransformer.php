<?php

namespace RealWorld\Transformers;

use function array_map;
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
     * @param Model $data
     * @return array
     */
    public function transform($data)
    {
        $article = clone $data;
        $data = $data->toArray();
        unset($data['id'], $data['userId']);

        // TODO: Add tagList, favorited and  favoritesCount.

        $tags = $article->tags->toArray();
        $data['tagList'][] = array_map(
            function ($tag) {
                return $tag['name'];
            },
            $tags
        );

        $data['favorited'] = false;
        $data['favoritesCount'] = false;

        $author = $article->user->toArray();
        $data['author'] = [
            'username' => $author['username'],
            'bio' => $author['bio'],
            'image' => $author['image'],
            'following' => $author['following'] ?? false,
        ];

        return $data;
    }
}
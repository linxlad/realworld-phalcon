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
     * @param Articles $data
     * @return array
     */
    public function transform(Articles $data)
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

        $data['favorited'] = array_map(function ($user) {
            if ($user = $this->loggedInUserId) {
                return true;
            }
        }, $article->favorites->toArray());
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
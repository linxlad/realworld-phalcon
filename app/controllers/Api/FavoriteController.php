<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Articles;
use RealWorld\Traits\AuthenticatedUserTrait;
use RealWorld\Transformers\ArticleTransformer;

/**
 * Class FavoriteController
 * @package RealWorld\Controllers\Api
 */
class FavoriteController extends ApiController
{
    use AuthenticatedUserTrait;

    /**
     * @param $slug
     * @return Response
     */
    public function addAction($slug)
    {
        if (!$article = Articles::findFirstBySlug($slug)) {
            return $this->respondNotFound();
        }

        $this->getAuthenticatedUser()->favorite($article);

        return $this->respondWithTransformer($article, new ArticleTransformer);
    }

    /**
     * @param $slug
     * @return Response
     */
    public function removeAction($slug)
    {
        if (!$article = Articles::findFirstBySlug($slug)) {
            return $this->respondNotFound();
        }

        $this->getAuthenticatedUser()->unFavorite($article);

        return $this->respondWithTransformer($article, new ArticleTransformer);
    }
}
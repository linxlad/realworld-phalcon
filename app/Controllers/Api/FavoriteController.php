<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\User;
use RealWorld\Repository\ArticleRepository;
use RealWorld\Transformers\ArticleTransformer;

/**
 * Class FavoriteController
 * @package RealWorld\Controllers\Api
 */
class FavoriteController extends ApiController
{
    /**
     * @var ArticleRepository
     */
    protected $articleRepo;

    /**
     * @var User
     */
    protected $authenticatedUser;

    /**
     *
     */
    public function initialize()
    {
        // Make sure the request does have a user (shouldn't get this far).
        if (!$this->request->user) {
            return $this->respondForbidden();
        }

        $this->articleRepo = $this->di->getRepository('article');
        $this->authenticatedUser = $this->request->user;
    }

    /**
     * @param $slug
     * @return Response
     */
    public function addAction($slug)
    {
        if (!$article = $this->articleRepo->firstBy(['slug' => $slug])) {
            return $this->respondNotFound();
        }

        $this->authenticatedUser->favorite($article);

        return $this->respondWithTransformer($article, new ArticleTransformer);
    }

    /**
     * @param $slug
     * @return Response
     */
    public function removeAction($slug)
    {
        if (!$article = $this->articleRepo->firstBy(['slug' => $slug])) {
            return $this->respondNotFound();
        }

        $this->authenticatedUser->unFavorite($article);

        return $this->respondWithTransformer($article, new ArticleTransformer);
    }
}
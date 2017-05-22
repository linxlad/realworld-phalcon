<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Exception;
use Phalcon\Http\Response;
use RealWorld\Models\Articles;
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

    /**
     * Update article
     *
     * @param $slug
     * @return Response
     */
    public function updateAction($slug)
    {
        try {
            if (!$article = $this->articleRepo->firstBy([
                'slug' => $slug,
                'userId' => $this->request->user->id
            ])) {
                return $this->respondUnauthorized();
            }

            $input = $this->getJsonInput('article');
            $article->applyInputToModel($input);

            if (!$result = $article->update()) {
                return $this->respondError($article->getMessages());
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respondWithTransformer($article, new ArticleTransformer);
    }

    /**
     * Deletes an existing article
     *
     * @param $slug
     * @return Response
     */
    public function deleteAction($slug)
    {
        if (!$article = $this->articleRepo->firstBy(['slug' => $slug])) {
            return $this->respondNotFound();
        }

        $article->delete();

        return $this->respondSuccess();
    }
}
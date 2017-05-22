<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Articles;
use RealWorld\Models\User;
use RealWorld\Repository\ArticleRepository;
use RealWorld\Transformers\ArticleTransformer;

/**
 * Class ArticleController
 * @package RealWorld\Controllers\Api
 */
class ArticleController extends ApiController
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
     * The start action, it returns the "search"
     *
     * @param $slug
     * @return Response
     */
    public function indexAction($slug)
    {
        // If it's a slug just grab the article.
        if ($slug && ($article = $this->articleRepo->firstBy(['slug' => $slug]))) {
            return $this->respondWithTransformer($article, new ArticleTransformer);
        }

        // Ok it's not a slug so let's filter on the query string.
        //...
    }

    /**
     * Creates a article based on the data entered in the "new" action
     */
    public function createAction()
    {
        try {
            $input = $this->getJsonInput('article');
            $article = new Articles();
            $article->applyInputToModel($input);

            $article->userId = $this->authenticatedUser->id;

            if (!$result = $article->create()) {
                return $this->respondError($article->getMessages());
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

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
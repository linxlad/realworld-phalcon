<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Articles;
use RealWorld\Models\Tags;
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
        $this->articleRepo = $this->di->getRepository('article');

        // Make sure the request does have a user (shouldn't get this far).
        if (!$this->request->user) {
            return ;
        }

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
        $query = $this->request->getQuery();
        $articles = null;

        // ARTICLES BY AUTHOR
        if (isset($query['author'])) {
            $user = User::findFirstByUsername($query['author']);

            $userId = $user ? $user->id : null;

            $articles = $this->articleRepo->getBy(['userId' => $userId]);
        }

        if (isset($query['favorited'])) {
            $user = User::findFirstByUsername($query['author']);


        }

        return $this->respondWithTransformer($articles, new ArticleTransformer);
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

            if (isset($input['tagList']) && !empty($input['tagList'])) {
                $tags = [];

                foreach($input['tagList'] as $name) {
                    if (!Tags::findFirstByName($name)) {
                        $tag = new Tags();
                        $tag->name = trim($name);
                        $tags[] = $tag;
                    }
                }

                $article->tags = $tags;
            }

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
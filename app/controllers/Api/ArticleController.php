<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Articles;
use RealWorld\Models\Tags;
use RealWorld\Models\User;
use RealWorld\Traits\AuthenticatedUserTrait;
use RealWorld\Transformers\ArticleTransformer;
use function var_dump;

/**
 * Class ArticleController
 * @package RealWorld\Controllers\Api
 */
class ArticleController extends ApiController
{
    use AuthenticatedUserTrait;

    /**
     * The start action, it returns the "search"
     *
     * @param $slug
     * @return Response
     */
    public function indexAction($slug)
    {
        // If it's a slug just grab the article.
        if ($slug && ($article = Articles::findFirstBySlug($slug))) {
            return $this->respondWithTransformer($article, new ArticleTransformer);
        }

        exit;
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

            var_dump($user->favorites->toAarray()); exit;
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
            $article->userId = $this->getAuthenticatedUser()->id;

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
            if (!$article = Articles::findFirst([
                "conditions" => "slug = :slug: AND userId = :userId:",
                "bind"       => [
                    "slug" => $slug,
                    "userId" => $this->getAuthenticatedUser()->id,
                ]
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
        if (!$article = Articles::findFirstBySlug($slug)) {
            return $this->respondNotFound();
        }

        $article->delete();

        return $this->respondSuccess();
    }
}
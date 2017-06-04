<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Articles;
use RealWorld\Models\Tags;
use RealWorld\Traits\ArticleFilterTrait;
use RealWorld\Traits\AuthenticatedUserTrait;
use RealWorld\Transformers\ArticleTransformer;
use Phalcon\Mvc\Model\Resultset;

/**
 * Class ArticleController
 * @package RealWorld\Controllers\Api
 */
class ArticleController extends ApiController
{
    use ArticleFilterTrait, AuthenticatedUserTrait;

    /**
     * The start action, it returns the "search"
     *
     * @return Response
     */
    public function indexAction()
    {
        $articles = $this->filterArticles();
        $this->setModelPaginated(true);

        if ($articles instanceof Resultset) {
            $articles = [
                'articles' => $this->respondWithTransformer(
                    $articles,
                    new ArticleTransformer
                )['articles'],
                'articlesCount' => $articles->count()
            ];
        }

        return $this->respond($articles);
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

                foreach ($input['tagList'] as $name) {
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
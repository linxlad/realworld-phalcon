<?php

namespace RealWorld\Controllers\Api;
use Phalcon\Exception;
use Phalcon\Http\Response;
use RealWorld\Filters\ArticleFilter;
use RealWorld\Models\Articles;
use RealWorld\Models\Tags;
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
     *
     */
    public function initialize()
    {
        $this->articleRepo = $this->di->getRepository('article');
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
            if (!$userInput = $this->request->getJsonRawBody(true)['article']) {
                throw new Exception('No article.');
            }

            $article = new Articles();

            foreach ($userInput as $field => $value) {
                $article->$field = $value;
            }

            $article->userId = $this->request->user->id;

            if (!$result = $article->create()) {
                return $this->respondError($article->getMessages());
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respondWithTransformer($article, new ArticleTransformer);
    }

    /**
     * Shows the view to return a "new" article
     */
    public function updateAction()
    {
        var_dump('Update'); exit;
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
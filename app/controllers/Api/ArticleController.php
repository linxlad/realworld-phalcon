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

    public function initialize()
    {

    }

    /**
     * The start action, it returns the "search"
     *
     * @param ArticleFilter $filter
     */
    public function indexAction($slug = null)
    {
        var_dump([$slug, $this->request->getQuery()]); exit;
        $filter = new ArticleFilter();
        $articles = Articles::find()->filter($filter);
        var_dump($articles); exit;
        $query = $this->request->getQuery();
        $articleRepo = new ArticleRepository();
        $result = $articleRepo->createNamedBuilder('a')
            ->leftJoin(Tags::class)
            ->where(Tags::class . '.name = :name:', ['name' => $query['tag']])
            ->getQuery()
            ->execute();
        var_dump($result->toArray()); exit;
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
        $articleRepo = new ArticleRepository();

        if (!$article = $articleRepo->firstBy(['slug' => $slug])) {
            return $this->respondNotFound();
        }

        $article->delete();

        return $this->respondSuccess();
    }
}
<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Articles;
use RealWorld\Models\Comments;
use RealWorld\Models\Tags;
use RealWorld\Models\User;
use RealWorld\Repository\ArticleRepository;
use RealWorld\Transformers\CommentTransformer;
use RealWorld\Transformers\TagTransformer;

/**
 * Class CommentController
 * @package RealWorld\Controllers\Api
 */
class CommentController extends ApiController
{
    /**
     * @var Articles;
     */
    protected $article;

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
        $this->articleRepo = $this->di->getRepository('article');
        $slug = $this->dispatcher->getParam('article');

        if (($slug && ($article = $this->articleRepo->firstBy(['slug' => $slug])))) {
            $this->article = $article;
        }

        if (!$this->request->user) {
            return;
        }

        $this->authenticatedUser = $this->request->user;
    }

    /**
     * Get all the comments of the article given by its slug.
     *
     * @return Response
     */
    public function indexAction($slug)
    {
        if (!$article = $this->article) {
            return $this->respondNotFound();
        }

        return $this->respondWithTransformer($article->comments, new CommentTransformer);
    }

    /**
     * Add a comment to the article given by its slug and return the comment if successful.
     *
     * @param $slug
     * @return Response
     */
    public function addAction($slug)
    {
        if (!$article = $this->article) {
            return $this->respondNotFound();
        }

        $input = $this->getJsonInput('comment');
        $comment = new Comments();
        $comment->body = trim($input['body']);
        $comment->userId = $this->authenticatedUser->id;
        $article->comments = $comment;

        try {
            if (!$result = $article->save()) {
                return $this->respondError($article->getMessages());
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respondWithTransformer($article->comments, new CommentTransformer);
    }

    /**
     * Delete the comment given by its id.
     *
     * @param $slug
     * @param $id
     * @return Response
     */
    public function deleteAction($slug, $id)
    {
        if (!$article = $this->article) {
            return $this->respondNotFound();
        }

        $article->comments->filter(
            function ($comment) use ($id, &$deleted) {
                if ($comment->id === $id) {
                    $comment->delete();
                }
            }
        );

        return $this->respondSuccess();
    }
}

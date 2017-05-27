<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
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
     * Get all the comments of the article given by its slug.
     *
     * @return Response
     */
    public function indexAction($slug)
    {
    }

    public function addAction($slug)
    {
        if (!($slug && ($article = $this->articleRepo->firstBy(['slug' => $slug])))) {
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
}

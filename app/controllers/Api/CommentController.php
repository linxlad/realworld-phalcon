<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Filter;
use Phalcon\Http\Response;
use RealWorld\Models\Articles;
use RealWorld\Models\Comments;
use RealWorld\Traits\AuthenticatedUserTrait;
use RealWorld\Transformers\CommentTransformer;

/**
 * Class CommentController
 * @package RealWorld\Controllers\Api
 */
class CommentController extends ApiController
{
    use AuthenticatedUserTrait;

    /**
     * Get all the comments of the article given by its slug.
     *
     * @param $slug
     *
     * @return Response
     */
    public function indexAction($slug)
    {
        if (!$article = Articles::findFirstBySlug($slug)) {
            return $this->respondNotFound();
        }

        return $this->respondWithTransformer($article->comments, new CommentTransformer);
    }

    /**
     * Add a comment to the article given by its slug and return the comment if successful.
     *
     * @param $slug
     *
     * @return Response
     */
    public function addAction($slug)
    {
        if (!$article = Articles::findFirstBySlug($slug)) {
            return $this->respondNotFound();
        }

        $input = $this->getJsonInput('comment');
        $comment = new Comments();
        $comment->body = (new Filter())->sanitize($input['body'], 'string');
        $comment->userId = $this->getAuthenticatedUser()->id;
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
     *
     * @return Response
     */
    public function deleteAction($slug, $id)
    {
        if (!$article = Articles::findFirstBySlug($slug)) {
            return $this->respondNotFound();
        }

        if ($comment = Comments::findFirst([
            "conditions" => "id = :user: AND articleId = :article:",
            "bind"       => [
                "user" => $id,
                "article" => $article->id,
            ]
        ])) {
            $comment->delete();
        }

        return $this->respondSuccess();
    }
}

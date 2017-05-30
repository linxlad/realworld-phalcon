<?php

namespace RealWorld\Controllers\Api;

use function array_push;
use function foo\func;
use Phalcon\Http\Response;
use RealWorld\Models\Articles;
use RealWorld\Models\Follows;
use RealWorld\Traits\AuthenticatedUserTrait;
use RealWorld\Transformers\ArticleTransformer;
use function var_dump;

/**
 * Class FeedController
 * @package RealWorld\Controllers\Api
 */
class FeedController extends ApiController
{
    use AuthenticatedUserTrait;

    /**
     * Get all the articles of the following users.
     *
     * @return Response
     */
    public function indexAction()
    {
        $followedIds = [];

        $this->getAuthenticatedUser()->follows->filter(
            function ($f) use (&$followedIds) {
                $followedIds[] = $f->followedId;
            }
        );

        if (!$article = Articles::find(
            [
                'userId IN ({followed:array})',
                'bind' => [
                    'followed' => $followedIds
                ]
            ]
        )) {
            return $this->respondNotFound();
        }

        return $this->respondWithTransformer($article, new ArticleTransformer);
    }
}

<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Articles;
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
        echo 'FeedController::indexAction'; exit;
        //$followingIds = $this->getAuthenticatedUser()->following->id;
        var_dump($this->getAuthenticatedUser()); exit;
        if (!$article = Articles::findByUserId($this->getAuthenticatedUser()->id)) {
            return $this->respondUnauthorized();
        }

        return $this->respondWithTransformer($article, new ArticleTransformer);
    }
}

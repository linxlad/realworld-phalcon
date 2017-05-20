<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;

class FeedController extends ApiController
{
    /**
     * Get all the articles of users that are followed by the authenticated user.
     *
     * @return Response
     */
    public function index()
    {
        $user = $this->request->user;
    }
}

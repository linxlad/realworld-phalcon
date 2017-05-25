<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Tags;

/**
 * Class TagController
 * @package RealWorld\Controllers\Api
 */
class TagController extends ApiController
{
    /**
     * Get all tags.
     *
     * @return Response
     */
    public function index()
    {
        $this->respond(Tags::find()->toArray());
    }
}

<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\Tags;
use RealWorld\Transformers\TagTransformer;

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
    public function indexAction()
    {
        $tags = [];

        foreach (Tags::find(['columns' => 'name']) as $tag) {
            $tags[] = $tag->name;
        }

        return $this->respondWithTransformer($tags, new TagTransformer);
    }
}

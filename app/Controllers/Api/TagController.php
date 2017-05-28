<?php

namespace RealWorld\Controllers\Api;

use function array_map;
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
        return $this->respondWithTransformer(
            array_map(
                function($tag) {
                    return $tag['name'];
                },
                Tags::find(['columns' => 'name'])->toArray()
            ),
            new TagTransformer
        );
    }
}

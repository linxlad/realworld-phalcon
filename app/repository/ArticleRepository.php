<?php

namespace RealWorld\Repository;

use RealWorld\Models\Articles;

/**
 * Class ArticleRepository
 * @package RealWorld\Repository
 */
class ArticleRepository extends Repository
{
    /**
     * ArticleRepository constructor.
     */
    public function __construct()
    {
        $this->model = new Articles();
    }

    /**
     * @return string
     */
    public function getModelName()
    {
        return Articles::class;
    }
}
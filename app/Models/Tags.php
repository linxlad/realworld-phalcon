<?php

namespace RealWorld\Models;

/**
 * Class Tags
 * @package RealWorld\Models
 */
class Tags extends Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=20, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("realworlddb");
        $this->hasManyToMany(
            'id',
            ArticleTag::class,
            'tagId',
            'articleId',
            Articles::class,
            'id',
            ['alias' => 'Articles']
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tags';
    }
}

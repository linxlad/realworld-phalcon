<?php

namespace RealWorld\Models;

/**
 * Class ArticleTag
 * @package RealWorld\Models
 */
class ArticleTag extends Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    public $article_id;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    public $tag_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("realworlddb");
        $this->belongsTo('articleId', Articles::class, 'id', ['alias' => 'Articles']);
        $this->belongsTo('tagId', Tags::class, 'id', ['alias' => 'Tags']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'article_tag';
    }
}

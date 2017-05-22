<?php

namespace RealWorld\Models;

/**
 * Class Favorites
 * @package RealWorld\Models
 */
class Favorites extends Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    public $userId;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    public $articleId;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $created;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $modified;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("realworlddb");
        $this->belongsTo('userId', User::class, 'id', ['alias' => 'User']);
        $this->belongsTo('articleId', Articles::class, 'id', ['alias' => 'Articles']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'favorites';
    }
}

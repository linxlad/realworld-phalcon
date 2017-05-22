<?php

namespace RealWorld\Models;

/**
 * Class Follows
 * @package RealWorld\Models
 */
class Follows extends Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    public $followerId;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    public $followedId;

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
        $this->belongsTo('followerId', User::class, 'id', ['alias' => 'User']);
        $this->belongsTo('followedId', User::class, 'id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'follows';
    }
}

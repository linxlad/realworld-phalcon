<?php
namespace RealWorld\Models;

use Phalcon\Mvc\Model\Behavior\Timestampable;
use RealWorld\Validators\CreateArticle;
use Phalcon\Utils\Slug;

/**
 * Class Articles
 * @package RealWorld\Models
 */
class Articles extends Model
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
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    public $userId;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $slug;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $title;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $description;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $body;

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
        $this->addBehavior(
            new Timestampable(
                [
                    "beforeCreate" => [
                        "field"  => "created",
                        "format" => 'Y-m-d H:i:s'
                    ],
                ]
            )
        );

        $this->addBehavior(
            new Timestampable(
                [
                    "beforeCreate" => [
                        "field"  => "modified",
                        "format" => 'Y-m-d H:i:s'
                    ],
                ]
            )
        );

        $this->setSchema("realworlddb");
        $this->hasMany('id', ArticleTag::class, 'article_id', ['alias' => 'ArticleTag']);
        $this->hasMany('id', Comments::class, 'article_id', ['alias' => 'Comments']);
        $this->hasMany('id', Favorites::class, 'article_id', ['alias' => 'Favorites']);
        $this->belongsTo('userId', User::class, 'id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'articles';
    }

    /**
     * @return bool
     */
    public function beforeCreate()
    {
        $validator = new CreateArticle();
        $validator->setEntity($this);

        return $this->validate($validator);
    }

    /**
     *
     */
    public function beforeValidationOnCreate()
    {
        // Create article slug.
        $this->slug = Slug::generate($this->title);
    }
}

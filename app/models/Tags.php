<?php

namespace RealWorld\Models;
use Phalcon\Db\AdapterInterface;
use Phalcon\Db\Column;
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Mvc\Model\MetaDataInterface;

/**
 * Class Tags
 * @package RealWorld\Models
 */
class Tags extends Model
{
    /**
     *
     * @var integer
     * @Identity
     * @Column(type="integer", length=20, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Primary
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

    /**
     * The metadata defined manually so `name`
     * can be used as a primary key.
     *
     * @return array
     */
    public function metaData()
    {
        return [
            // Every column in the mapped table
            MetaData::MODELS_ATTRIBUTES => [
                "id",
                "name"
            ],

            // Every column part of the primary key
            MetaData::MODELS_PRIMARY_KEY => [
                'name'
            ],

            // Every column that isn't part of the primary key
            MetaData::MODELS_NON_PRIMARY_KEY => [],

            // Every column that doesn't allows null values
            MetaData::MODELS_NOT_NULL => [
                "id",
                "name"
            ],

            // Every column and their data types
            MetaData::MODELS_DATA_TYPES => [
                "id"   => Column::TYPE_INTEGER,
                "name" => Column::TYPE_VARCHAR
            ],

            // The columns that have numeric data types
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                "id"   => true
            ],

            MetaData::MODELS_IDENTITY_COLUMN => "id",

            // How every column must be bound/casted
            MetaData::MODELS_DATA_TYPES_BIND => [
                "id"   => Column::BIND_PARAM_INT,
                "name" => Column::BIND_PARAM_STR,
            ],

            // Fields that must be ignored from INSERT SQL statements
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],

            // Fields that must be ignored from UPDATE SQL statements
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],

            // Default values for columns
            MetaData::MODELS_DEFAULT_VALUES => [],

            // Fields that allow empty strings
            MetaData::MODELS_EMPTY_STRING_VALUES => [],
        ];
    }
}

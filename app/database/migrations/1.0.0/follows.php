<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class FollowsMigration_100
 */
class FollowsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('follows', [
                'columns' => [
                    new Column(
                        'follower_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 20,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'followed_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'follower_id'
                        ]
                    ),
                    new Column(
                        'created',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'followed_id'
                        ]
                    ),
                    new Column(
                        'modified',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'created'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['follower_id', 'followed_id'], 'PRIMARY'),
                    new Index('follows_ibfk_2', ['followed_id'], null)
                ],
                'references' => [
                    new Reference(
                        'follows_ibfk_1',
                        [
                            'referencedTable' => 'users',
                            'columns' => ['follower_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'follows_ibfk_2',
                        [
                            'referencedTable' => 'users',
                            'columns' => ['followed_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'CASCADE'
                        ]
                    )
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_bin'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `offer`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `category`
 * - `country`
 */
class m171117_063525_create_offer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('offer', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->defaultValue(NULL),
            'name' => $this->string(),
            'num' => $this->integer(),
            'body' => $this->text(),
            'land' => $this->string(),
            'preland' => $this->string(),
            'status' => $this->integer(),
            'geo_id' => $this->integer()->notNull(),
            'priority' => $this->integer(),
            'dcreated' => $this->integer(),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            'idx-offer-author_id',
            'offer',
            'author_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-offer-author_id',
            'offer',
            'author_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            'idx-offer-category_id',
            'offer',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-offer-category_id',
            'offer',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );

        // creates index for column `geo_id`
        $this->createIndex(
            'idx-offer-geo_id',
            'offer',
            'geo_id'
        );

        // add foreign key for table `country`
        $this->addForeignKey(
            'fk-offer-geo_id',
            'offer',
            'geo_id',
            'country',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-offer-author_id',
            'offer'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-offer-author_id',
            'offer'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-offer-category_id',
            'offer'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-offer-category_id',
            'offer'
        );

        // drops foreign key for table `country`
        $this->dropForeignKey(
            'fk-offer-geo_id',
            'offer'
        );

        // drops index for column `geo_id`
        $this->dropIndex(
            'idx-offer-geo_id',
            'offer'
        );

        $this->dropTable('offer');
    }
}

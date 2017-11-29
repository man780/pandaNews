<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `category`
 * - `country`
 */
class m171117_062849_create_news_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->defaultValue(NULL),
            'title' => $this->string(),
            'body' => $this->text(),
            'status' => $this->integer(),
            'geo_id' => $this->integer()->notNull(),
            'priority' => $this->integer(),
            'time' => $this->integer(),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            'idx-news-author_id',
            'news',
            'author_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-news-author_id',
            'news',
            'author_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            'idx-news-category_id',
            'news',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-news-category_id',
            'news',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );

        // creates index for column `geo_id`
        $this->createIndex(
            'idx-news-geo_id',
            'news',
            'geo_id'
        );

        // add foreign key for table `country`
        $this->addForeignKey(
            'fk-news-geo_id',
            'news',
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
            'fk-news-author_id',
            'news'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-news-author_id',
            'news'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-news-category_id',
            'news'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-news-category_id',
            'news'
        );

        // drops foreign key for table `country`
        $this->dropForeignKey(
            'fk-news-geo_id',
            'news'
        );

        // drops index for column `geo_id`
        $this->dropIndex(
            'idx-news-geo_id',
            'news'
        );

        $this->dropTable('news');
    }
}

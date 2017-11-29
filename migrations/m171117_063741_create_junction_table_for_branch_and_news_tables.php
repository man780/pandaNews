<?php

use yii\db\Migration;

/**
 * Handles the creation of table `branch_news`.
 * Has foreign keys to the tables:
 *
 * - `branch`
 * - `news`
 */
class m171117_063741_create_junction_table_for_branch_and_news_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('branch_news', [
            'branch_id' => $this->integer(),
            'news_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'status' => $this->integer(),
            'PRIMARY KEY(branch_id, news_id)',
        ]);

        // creates index for column `branch_id`
        $this->createIndex(
            'idx-branch_news-branch_id',
            'branch_news',
            'branch_id'
        );

        // add foreign key for table `branch`
        $this->addForeignKey(
            'fk-branch_news-branch_id',
            'branch_news',
            'branch_id',
            'branch',
            'id',
            'CASCADE'
        );

        // creates index for column `news_id`
        $this->createIndex(
            'idx-branch_news-news_id',
            'branch_news',
            'news_id'
        );

        // add foreign key for table `news`
        $this->addForeignKey(
            'fk-branch_news-news_id',
            'branch_news',
            'news_id',
            'news',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `branch`
        $this->dropForeignKey(
            'fk-branch_news-branch_id',
            'branch_news'
        );

        // drops index for column `branch_id`
        $this->dropIndex(
            'idx-branch_news-branch_id',
            'branch_news'
        );

        // drops foreign key for table `news`
        $this->dropForeignKey(
            'fk-branch_news-news_id',
            'branch_news'
        );

        // drops index for column `news_id`
        $this->dropIndex(
            'idx-branch_news-news_id',
            'branch_news'
        );

        $this->dropTable('branch_news');
    }
}

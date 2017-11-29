<?php

use yii\db\Migration;

/**
 * Handles the creation of table `branch_offer`.
 * Has foreign keys to the tables:
 *
 * - `branch`
 * - `offer`
 */
class m171117_063809_create_junction_table_for_branch_and_offer_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('branch_offer', [
            'branch_id' => $this->integer(),
            'offer_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'status' => $this->integer(),
            'PRIMARY KEY(branch_id, offer_id)',
        ]);

        // creates index for column `branch_id`
        $this->createIndex(
            'idx-branch_offer-branch_id',
            'branch_offer',
            'branch_id'
        );

        // add foreign key for table `branch`
        $this->addForeignKey(
            'fk-branch_offer-branch_id',
            'branch_offer',
            'branch_id',
            'branch',
            'id',
            'CASCADE'
        );

        // creates index for column `offer_id`
        $this->createIndex(
            'idx-branch_offer-offer_id',
            'branch_offer',
            'offer_id'
        );

        // add foreign key for table `offer`
        $this->addForeignKey(
            'fk-branch_offer-offer_id',
            'branch_offer',
            'offer_id',
            'offer',
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
            'fk-branch_offer-branch_id',
            'branch_offer'
        );

        // drops index for column `branch_id`
        $this->dropIndex(
            'idx-branch_offer-branch_id',
            'branch_offer'
        );

        // drops foreign key for table `offer`
        $this->dropForeignKey(
            'fk-branch_offer-offer_id',
            'branch_offer'
        );

        // drops index for column `offer_id`
        $this->dropIndex(
            'idx-branch_offer-offer_id',
            'branch_offer'
        );

        $this->dropTable('branch_offer');
    }
}

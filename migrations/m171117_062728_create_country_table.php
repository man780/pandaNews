<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country`.
 */
class m171117_062728_create_country_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('country', [
            'id' => $this->primaryKey(),
            'cc_fips' => $this->string(),
            'cc_iso' => $this->string(),
            'tld' => $this->string(),
            'country_name' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('country');
    }
}

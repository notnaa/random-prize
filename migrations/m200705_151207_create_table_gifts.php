<?php

use yii\db\Migration;

/**
 * Class m200705_151207_create_table_gifts
 */
class m200705_151207_create_table_gifts extends Migration
{
    /** @var string */
    private const TABLE_NAME = '{{%gifts}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}

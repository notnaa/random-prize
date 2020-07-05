<?php

use yii\db\Migration;

/**
 * Class m200705_151157_create_table_users
 */
class m200705_151157_create_table_users extends Migration
{
    /** @var string */
    private const TABLE_NAME = '{{%users}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'email' => $this->string()->unique()->notNull(),
            'password' => $this->string(),
            'auth_key' => $this->string(),
            'loyalty_point' => $this->bigInteger()->unsigned()->defaultValue(0),
            'created_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('current_timestamp()'),
            'updated_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('current_timestamp() on update current_timestamp()'),
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

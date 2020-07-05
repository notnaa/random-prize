<?php

use yii\db\Migration;

/**
 * Class m200705_151225_create_table_user_prizes
 */
class m200705_151225_create_table_user_prizes extends Migration
{
    /** @var string */
    private const TABLE_NAME = '{{%user_prizes}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'entity_class' => $this->string()->notNull(),
            'user_id' => $this->bigInteger()->unsigned()->notNull(),
            'data' => $this->text()->notNull(),
            'status' => $this->integer(3)->unsigned()->notNull(),
            'created_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('current_timestamp()'),
            'updated_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('current_timestamp() on update current_timestamp()'),
        ]);

        $this->createIndex('idx-multi-user_prizes', self::TABLE_NAME, ['user_id', 'entity_class']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}

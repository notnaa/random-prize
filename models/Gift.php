<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Gift
 * @package app\models
 *
 * @property int $id
 * @property string $name
 */
class Gift extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%gifts}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'filter', 'filter' => 'trim'],
        ];
    }
}

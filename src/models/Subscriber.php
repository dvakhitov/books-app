<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "subscriber".
 *
 * @property int $id
 * @property string $phone
 * @property string|null $verification_code
 * @property int|null $is_verified
 * @property string|null $created_at
 *
 * @property Subscription[] $subscriptions
 */
final class Subscriber extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'subscriber';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['verification_code'], 'default', 'value' => null],
            [['is_verified'], 'default', 'value' => 0],
            [['phone'], 'required'],
            [['is_verified'], 'integer'],
            [['created_at'], 'safe'],
            [['phone'], 'string', 'max' => 20],
            [['verification_code'], 'string', 'max' => 10],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                => 'ID',
            'phone'             => 'Phone',
            'verification_code' => 'Verification Code',
            'is_verified'       => 'Is Verified',
            'created_at'        => 'Created At',
        ];
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return ActiveQuery
     */
    public function getSubscriptions(): ActiveQuery
    {
        return $this->hasMany(Subscription::class, ['subscriber_id' => 'id']);
    }

}

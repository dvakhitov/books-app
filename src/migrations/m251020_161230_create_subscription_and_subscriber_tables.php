<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscription}}`.
 */
class m251020_161230_create_subscription_and_subscriber_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        // Таблица subscriber
        $this->createTable('{{%subscriber}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(20)->notNull()->unique(),
            'verification_code' => $this->string(10),
            'is_verified' => $this->boolean()->defaultValue(false),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Таблица subscription
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'subscriber_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Индексы и связи
        $this->addForeignKey(
            'fk_subscription_subscriber',
            '{{%subscription}}',
            'subscriber_id',
            '{{%subscriber}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_subscription_author',
            '{{%subscription}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropForeignKey('fk_subscription_author', '{{%subscription}}');
        $this->dropForeignKey('fk_subscription_subscriber', '{{%subscription}}');
        $this->dropTable('{{%subscription}}');
        $this->dropTable('{{%subscriber}}');
    }
}

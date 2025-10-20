<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m251020_122609_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('author', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
        ]);

        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->integer(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->unique()->notNull(),
            'cover' => $this->string(255),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
        ]);

        $this->createTable('book_author', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'PRIMARY KEY(book_id, author_id)',
        ]);
        $this->addForeignKey('fk_book_author_book','book_author','book_id','book','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_book_author_author','book_author','author_id','author','id','CASCADE','CASCADE');

        $this->createTable('author_subscription', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
        ]);
        $this->createIndex('idx_author_subscription_unique','author_subscription',['author_id','phone'],true);
        $this->addForeignKey('fk_author_subscription_author','author_subscription','author_id','author','id','CASCADE','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('author_subscription');
        $this->dropForeignKey('fk_book_author_author','book_author');
        $this->dropForeignKey('fk_book_author_book','book_author');
        $this->dropTable('book_author');
        $this->dropTable('book');
        $this->dropTable('author');
    }
}

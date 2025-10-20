<?php

namespace app\commands;

use app\models\Author;
use app\models\Book;
use yii\console\Controller;
use yii\helpers\Console;

class ImportController extends Controller
{
    /**
     * Импорт книг из JSON файла
     * Запуск: import/books
     */
    public function actionBooks(): int
    {
        $file = \Yii::getAlias('@app/data/books.json'); // путь к твоему файлу
        if (!file_exists($file)) {
            $this->stderr("❌ File not found: $file\n", Console::FG_RED);

            return 1;
        }

        $json = file_get_contents($file);
        $data = json_decode($json, true);

        if (!$data) {
            $this->stderr("❌ Error parsing JSON\n", Console::FG_RED);

            return 1;
        }

        $count = 0;

        foreach ($data as $item) {
            $book              = Book::findOne(['isbn' => $item['isbn'] ?? null]) ?? new Book();
            $book->title       = $item['title']                                   ?? '';
            $book->description = $item['shortDescription']                        ?? $item['longDescription'] ?? '';
            $book->cover       = $item['thumbnailUrl']                            ?? null;
            if (!empty($item['publishedDate']['$date'])) {
                $book->year = date('Y', strtotime($item['publishedDate']['$date']));
            }

            $book->isbn = $item['isbn'] ?? null;
            if (!$book->isbn) {
                continue;
            }
            if (!$book->save()) {
                $this->stderr("❌ Error saving book: {$book->title}\n", Console::FG_RED);
                continue;
            }

            // Создаем авторов и связи
            if (!empty($item['authors'])) {
                foreach ($item['authors'] as $authorName) {
                    $authorName = trim($authorName);
                    if ($authorName === '') {
                        $this->stdout("⚠ Skipping empty author name\n", Console::FG_YELLOW);
                        continue;
                    }

                    $author = Author::findOne(['name' => $authorName]);
                    if (!$author) {
                        $author = new Author(['name' => $authorName]);
                        if (!$author->save()) {
                            $errors = implode(', ', $author->getFirstErrors());
                            $this->stderr("❌ Error saving author: {$authorName}. Errors: $errors\n", Console::FG_RED);
                            continue;
                        } else {
                            $this->stdout("✅ Author created: {$authorName}\n", Console::FG_GREEN);
                        }
                    } else {
                        $this->stdout("ℹ Author exists: {$authorName}\n", Console::FG_YELLOW);
                    }

                    // Связь book ↔ author
                    $exists = new \yii\db\Query()
                        ->from('book_author')
                        ->where(['book_id' => $book->id, 'author_id' => $author->id])
                        ->exists();

                    if (!$exists) {
                        $book->link('authors', $author);
                    }
                }
            }


            $count++;
            if ($count % 20 === 0) {
                $this->stdout("✅ Imported $count books...\n", Console::FG_GREEN);
            }
        }

        $this->stdout("Imported $count books.\n", Console::FG_GREEN);

        return 0;
    }
}

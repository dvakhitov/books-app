<?php

namespace app\controllers;

use app\models\Author;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

final class ReportController extends Controller
{
    public function actionTopAuthors(?string $year = null): string
    {
        $year = $year ?? date('Y');

        $query = Author::find()
            ->select(['author.id', 'author.name', 'COUNT(book_author.book_id) AS books_count'])
            ->join('JOIN', 'book_author', 'book_author.author_id = author.id')
            ->join('JOIN', 'book', 'book.id = book_author.book_id')
            ->where(['book.year' => $year])
            ->groupBy('author.id')
            ->orderBy(['books_count' => SORT_DESC])
            ->limit(10)
            ->asArray();

        $authors      = $query->all();
        $dataProvider = new ArrayDataProvider([
            'allModels'  => $authors,
            'pagination' => false,
        ]);

        return $this->render('top-authors', [
            'dataProvider' => $dataProvider,
            'year'         => $year,
        ]);
    }

}

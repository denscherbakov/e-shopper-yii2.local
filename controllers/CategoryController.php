<?php
/**
 * Created by PhpStorm.
 * User: Papka
 * Date: 25.09.2017
 * Time: 16:34
 */

namespace app\controllers;

use app\models\Category;
use app\models\Product;
use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class CategoryController extends AppController
{
    public function actionIndex()
    {
        $this->setMeta('Yii2 e-shop');
        $hits = Product::find()->where(['hit' => '1'])->limit(6)->all();
        return $this->render('index', compact('hits'));
    }

    public function actionView($id)
    {
        $category = Category::findOne($id);

        if (!$category){
            throw new HttpException(404, 'Такой категории не существует');
        }

        $query = Product::find()->where(['category_id' => $id]);

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 3,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();

        $this->setMeta('Yii2 e-shop | ' . $category->name, $category->keywords, $category->description);
        return $this->render('view', compact('products','pages', 'category'));
    }

    public function actionSearch()
    {
        $search = trim(Yii::$app->request->get('search'));

        $this->setMeta('Yii2 e-shop | Search ');

        if (!$search) return $this->render('search');

        $query = Product::find()->where(['like', 'name', $search]);

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 10,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('search', compact('products','pages', 'search'));
    }
}
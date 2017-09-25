<?php
/**
 * Created by PhpStorm.
 * User: Papka
 * Date: 25.09.2017
 * Time: 23:58
 */

namespace app\controllers;


use app\models\Product;
use yii\web\HttpException;

class ProductController extends AppController
{
    public function actionView($id)
    {
        $product = Product::findOne($id);

        if (!$product){
            throw new HttpException(404, 'Такого товара не существует');
        }

        $hits = Product::find()->where(['hit' => '1'])->limit(4)->all();

        $this->setMeta('Yii2 e-shop | ' . $product->name, $product->keywords, $product->description);

        return $this->render('view', compact('product', 'hits'));
    }
}
<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'id' =>(int) $category->id,
            'title' => (string) $category->name,
            'details' => (string) $category->description,
            'creationDate' =>(string) $category->created_at,
            'lastChange' => (string)$category->updated_at,
            'deletedDate' => (string)isset($category->deleted_at) ? $category->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show',$category->id)
                ],
                [
                'rel' => 'category.buyers',
                'href' => route('categories.buyers.index',$category->id)
               ],
            [
                'rel' => 'category.products',
                'href' => route('categories.products.index',$category->id)
            ],
            [
                'rel' => 'category.sellers',
                'href' => route('categories.sellers.index',$category->id)
            ],
            [
                'rel' => 'category.transactions',
                'href' => route('categories.transactions.index',$category->id)
            ]
                ]
        ];

    }

    public static function originalAttributes($index)
    {
        $attributes =[
            'id' => 'id',
            'title' => 'name',
            'details' => 'description',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }

    public static function transformAttributes($index)
    {
        $attributes =[
            'id' => 'id',
             'name' => 'title',
             'description' => 'details',
             'created_at' => 'creationDate',
             'updated_at' => 'lastChange',
             'deleted_at' => 'deleteDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }
}

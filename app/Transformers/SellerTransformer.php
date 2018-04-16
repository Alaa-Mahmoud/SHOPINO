<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'id' => (int)$seller->id,
            'name' => (string)$seller->name,
            'email' => (string)$seller->email,
            'isVerified' => (int)$seller->verified,
            'isAdmin' => ($seller->admin === 'true'),
            'creationDate' =>(string) $seller->created_at,
            'lastChange' => (string)$seller->updated_at,
            'deletedDate' => (string) isset($seller->deleted_at) ? $seller->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sellers.show',$seller->id)
                ],
                [
                    'rel' => 'seller.categories',
                    'href' => route('sellers.categories.index',$seller->id)
                ],
                [
                    'rel' => 'seller.products',
                    'href' => route('sellers.products.index',$seller->id)
                ],
                [
                    'rel' => 'buyers',
                    'href' => route('sellers.buyers.index',$seller->id)
                ],
                [
                    'rel' => 'transactions',
                    'href' => route('sellers.transactions.index',$seller->id)
                ],
                [
                    'rel' => 'profile',
                    'href' => route('users.show',$seller->id)
                ]
            ]
        ];
    }
    public static function originalAttributes($index)
    {
        $attributes =[
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'isAdmin' => 'admin',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }

    public static function transformAttributes($index)
    {
        $attributes =[
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'admin' => 'isAdmin',
            'created_at'=> 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at'=> 'deletedDate'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }
}

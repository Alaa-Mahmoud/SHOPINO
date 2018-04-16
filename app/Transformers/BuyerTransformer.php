<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'id' => (int)$buyer->id,
            'name' => (string)$buyer->name,
            'email' => (string)$buyer->email,
            'isVerified' => (int)$buyer->verified,
            'isAdmin' => ($buyer->admin === 'true'),
            'creationDate' =>(string) $buyer->created_at,
            'lastChange' => (string)$buyer->updated_at,
            'deletedDate' => (string) isset($buyer->deleted_at) ? $buyer->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('buyers.show',$buyer->id)
                ],
                [
                    'rel' => 'buyer.categories',
                    'href' => route('buyers.categories.index',$buyer->id)
                ],
                [
                    'rel' => 'buyer.products',
                    'href' => route('buyers.products.index',$buyer->id)
                ],
                [
                    'rel' => 'buyer.sellers',
                    'href' => route('buyers.sellers.index',$buyer->id)
                ],
                [
                    'rel' => 'buyer.transactions',
                    'href' => route('buyers.transactions.index',$buyer->id)
                ],
                [
                    'rel' => 'profile',
                    'href' => route('users.show',$buyer->id)
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

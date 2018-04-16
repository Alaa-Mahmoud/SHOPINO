<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id' =>(int) $transaction->id,
            'quantity' => (int) $transaction->quantity,
            'buyer' => (int) $transaction->buyer_id,
            'product' => (int) $transaction->product_id,
            'creationDate' =>(string) $transaction->created_at,
            'lastChange' => (string)$transaction->updated_at,
            'deletedDate' => (string)isset($transaction->deleted_at) ? $transaction->deleted_at : null,
            'links' => [
                [
                   'rel' => 'self',
                   'href' => route('transactions.show',$transaction->id)
                ],
                [
                    'rel' => 'transactions.categories',
                    'href' => route('transactions.categories.index',$transaction->id)
                ],
                [
                    'rel' => 'transactions.seller',
                    'href' => route('transactions.sellers.index',$transaction->id)
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show',$transaction->buyer_id)
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show',$transaction->product_id)
                ],


            ]
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes =[
            'id' => 'id',
            'quantity' => 'quantity',
            'buyer' => 'buyer_id',
            'product' => 'product_id',
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
            'quantity' => 'quantity',
            'buyer_id' => 'buyer',
            'product_id' =>  'product',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deleteDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }
}

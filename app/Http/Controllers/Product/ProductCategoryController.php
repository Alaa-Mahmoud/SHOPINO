<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.cradentials')->only(['index']);

    }

    public function index(Product $product)
 {
     $categories = $product->categories;
     return $this->showAll($categories);
 }

 public function update(Request $request , Product $product , Category $category)
 {
     $product->categories()->syncWithoutDetaching([$category->id]);
     return $this->showAll($product->categories);
 }

 public function destroy(Product $product , Category $category)
 {
     if(!$product->categories()->find($category->id))
     {
         return $this->errorResponse("no category found to be deleted",404);
     }

     $product->categories()->detach($category->id);

     return $this->showAll($product->categories);


 }


}

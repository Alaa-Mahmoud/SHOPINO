<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
  public function index(Category $category)
  {
      $transactions = $category->products()->whereHas('transactions')
                 ->with('transactions')->get()->pluck('transactions');
      return $this->showAll($transactions);
  }
}

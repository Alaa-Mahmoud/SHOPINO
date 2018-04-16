<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponer;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct()
    {
    }

    use ApiResponer;
}

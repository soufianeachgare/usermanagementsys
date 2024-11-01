<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as baseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends baseController
{
    use AuthorizesRequests, ValidatesRequests;
}

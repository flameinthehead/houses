<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    private $service;

    public function __construct(SearchService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->search($request);
    }
}

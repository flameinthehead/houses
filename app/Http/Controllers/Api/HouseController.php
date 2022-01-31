<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\SearchService;

class HouseController extends Controller
{
    private $service;

    public function __construct(SearchService $service)
    {
        $this->service = $service;
    }

    public function index(SearchRequest $request)
    {
        return $this->service->search($request);
    }
}

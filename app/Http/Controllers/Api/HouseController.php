<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use App\Validators\SearchValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HouseController extends Controller
{
    private $service;
    private $validator;

    public function __construct(SearchService $service, SearchValidator $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    public function index(Request $request)
    {
        if(!$this->validator->validate()){
            return response()->json([
                'success' => false,
                'message' => $this->validator->getErrorMessage(),
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->service->search($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreationRequest;
use App\Http\Requests\SubscribeRequest;
use App\Models\Website;
use App\Services\WebsiteService;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    private WebsiteService $websiteService;

    public function __construct(WebsiteService $websiteService) {
        $this->websiteService = $websiteService;
    }

    public function createPost(PostCreationRequest $request, $id) {
        return $this->websiteService->createPost($request, $id);
    }

    public function subscribe(SubscribeRequest $request, $id) {
        return $this->websiteService->subscribe($request, $id);
    }
}

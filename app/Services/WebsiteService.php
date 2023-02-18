<?php

namespace App\Services;

use App\Http\Requests\PostCreationRequest;
use App\Http\Requests\SubscribeRequest;
use App\Mail\PostCreatedNotification;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class WebsiteService {

    public function createPost(PostCreationRequest $request, $id) {
        $data = $request->validated();

        $post = Post::create([
            'website_id' => $id,
            'title' => $data['title'],
            'description' => $data['description']
        ]);

        $website = Website::find($id);
        $website->posts()->save($post);

        $all_subscribers = $website->subscriptions()->whereNull('notification_sent_at')->get();

        foreach ($all_subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new PostCreatedNotification($post));
            $subscriber->notification_sent_at = Carbon::now();
        }

        return response()->json(['message' => 'The post is created and notification sent to emails!']);
    }

    public function subscribe(Request $request, $id) {
        $data = $request->validate([
            'email' => Rule::unique('subscriptions', 'email')
                ->where('website_id', $id)
                ->ignore($id)
        ]);

        $subscription = Subscription::create([
            'website_id' => $id,
            'email' => $data['email']
        ]);

        $website = Website::find($id);
        $website->subscriptions()->save($subscription);

        return response()->json(['message' => 'Subscribed successfully']);
    }

}

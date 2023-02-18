<?php

namespace App\Console\Commands;

use App\Mail\PostCreatedNotification;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-email-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to the subscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $posts = Post::where('created_at', '>', Carbon::now()->subHour())
            ->whereHas('website.subscriptions', function($q) {
                $q->whereNull('notification_sent_at');
            })
            ->with('website.subscriptions')
            ->get();

        foreach ($posts as $post) {
            foreach ($post->website->subscriptions as $sub) {
                if ($post->created_at > $sub->notification_sent_at || !$sub->notification_sent_at) {
                    Mail::to($sub->email)->send(new PostCreatedNotification($post));
                    $sub->notification_sent_at = Carbon::now();
                }
            }
        }
    }
}

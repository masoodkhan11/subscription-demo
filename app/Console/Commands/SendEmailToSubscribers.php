<?php

namespace App\Console\Commands;

use App\Mail\NewPostPublished;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Log;

class SendEmailToSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post-update:subscribers {post}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $post = Post::query()
            ->with('website.subscriptions.user')
            ->whereId($this->argument('post'))
            ->firstOrFail();

        $post->website->subscriptions->each(function ($subscription) use ($post) {
            Log::debug("Send Email :: {$subscription->user->name} :: {$post->id}");
            
            Mail::to($subscription->user->email)
                ->send(new NewPostPublished($post->title, $post->description));
        });
    }
}

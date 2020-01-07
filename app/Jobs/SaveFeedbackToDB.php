<?php

namespace App\Jobs;

use App\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class SaveFeedbackToDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $request_data)
    {
        $this->request_data = $request_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // at first we have to create the feedback.
        $feedback = Feedback::create($this->request_data);

        // then we will assign feedback to it's state.
        $feedback->state()->create($this->request_data);
    }
}

<?php

namespace App\Console\Commands;

use App\Feedback;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feedback:reindex {delete?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all feedback to Elasticsearch';

    private $elastic_search;

    /**
     * Create a new command instance.
     *
     * @param Client $elastic_search
     */
    public function __construct(Client $elastic_search)
    {
        parent::__construct();

        $this->elastic_search = $elastic_search;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Indexing all feedback. This might take a while...');

        foreach (Feedback::cursor() as $feedback) {
            $this->elastic_search->index([
                'index' => $feedback->getSearchIndex(),
                'type' => $feedback->getSearchType(),
                'id' => $feedback->getKey(),
                'body' => $feedback->toSearchArray(),
            ]);
        }

        $this->info("\nDone!");
    }
}

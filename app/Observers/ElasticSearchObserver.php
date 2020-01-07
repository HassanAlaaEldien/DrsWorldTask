<?php

namespace App\Observers;

use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class ElasticSearchObserver
{
    /**
     * @var Client
     */
    private $elastic_search;

    /**
     * ElasticSearchObserver constructor.
     * @param Client $elastic_search
     */
    public function __construct(Client $elastic_search)
    {
        $this->elastic_search = $elastic_search;
    }

    /**
     * @param Model $model
     */
    public function created(Model $model): void
    {
        $this->elastic_search->index([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'id' => $model->getKey(),
            'body' => $model->toSearchArray(),
        ]);
    }

    /**
     * @param Model $model
     */
    public function deleted(Model $model): void
    {
        $this->elastic_search->delete([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'id' => $model->getKey(),
        ]);
    }
}

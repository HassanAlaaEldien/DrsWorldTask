<?php
/**
 * Created by PhpStorm.
 * User: hassan
 * Date: 1/6/20
 * Time: 2:37 PM
 */

namespace App\Search;

use App\Feedback;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ElasticRepository implements FeedbackRepository
{
    /**
     * @var Client
     */
    private $elastic_search;


    /**
     * ElasticSearchRepository constructor.
     * @param Client $elastic_search
     */
    public function __construct(Client $elastic_search)
    {
        $this->elastic_search = $elastic_search;
    }

    /**
     * @param string $query
     * @return array
     */
    public function search(string $query): array
    {
        $items = $this->searchElasticsearch($query);

        return Arr::pluck($items['hits']['hits'], '_source');
    }

    /**
     * @param string $query
     * @param Model model
     * @return array
     */
    private function searchElasticsearch(string $query): array
    {
        $model = new Feedback;

        $items = $this->elastic_search->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'query' => [
                    'match' => [
                        'company_token' => [
                            'query' => $query
                        ]
                    ]
                ],
            ],
        ]);

        return $items;
    }
}

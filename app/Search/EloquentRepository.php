<?php
/**
 * Created by PhpStorm.
 * User: hassan
 * Date: 1/6/20
 * Time: 3:09 PM
 */

namespace App\Search;


use App\Feedback;

class EloquentRepository implements FeedbackRepository
{
    /**
     * @param string $query
     * @return array
     */
    public function search(string $query): array
    {
        return Feedback::where('company_token', $query)->get()->toArray();
    }
}

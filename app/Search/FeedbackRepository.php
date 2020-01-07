<?php
/**
 * Created by PhpStorm.
 * User: hassan
 * Date: 1/6/20
 * Time: 2:35 PM
 */

namespace App\Search;

interface FeedbackRepository
{
    /**
     * @param string $query
     * @return array
     */
    public function search(string $query): array;
}

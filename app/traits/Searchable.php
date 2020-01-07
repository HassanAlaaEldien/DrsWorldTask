<?php

namespace App\traits;

use App\Observers\ElasticSearchObserver;

trait Searchable
{
    public static function bootSearchable()
    {
        if (config('services.search.enabled')) {
            static::observe(ElasticSearchObserver::class);
        }
    }

    /**
     * @return string
     */
    public function getSearchIndex(): string
    {
        return $this->getTable();
    }

    /**
     * @return string
     */
    public function getSearchType(): string
    {
        if (property_exists($this, 'customSearchType')) {
            return $this->customSearchType;
        }

        return $this->getTable();
    }

    /**
     * @return array
     */
    public function toSearchArray(): array
    {
        return $this->toArray();
    }
}

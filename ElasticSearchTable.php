<?php

namespace Pionect\Backoffice\ContentElements\Tables;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Pionect\Backoffice\Models\BaseRepository;
use Pionect\PertwoRepositories\Product\Search\Elasticsearch\Result;
use Elasticsearch\Client;

abstract class ElasticSearchTable extends Table
{
    /**
     * @var Client
     */
    protected $client;

    public $baseColumnClass = ElasticSearchColumn::class;

    public $baseFilterClass = ElasticSearchFilter::class;

    public function __construct(QueryCache $queryCache)
    {
        $this->client = resolve(Client::class);

        parent::__construct($queryCache);
    }

    /**
     * @param bool $isAggregateQuery
     * @return array
     */
    protected function getParams($isAggregateQuery = false, $paginate = true)
    {
        $params = [
            'index' => config('elasticsearch.index'),
            'type' => $this->type,
            'from' => $paginate ? $this->pageFirstItem() : 0,
            'size' => $paginate ? $this->size : 100,
            'sort' => $this->sortColumnName . ':' . $this->sortTargetOrder,
            'body' => $this->body($isAggregateQuery)
        ];

        return $params;
    }

    /**
     * @return array
     */
    protected function body($isAggregateQuery)
    {
        $body = [];

        if ($this->searchQuery) {

            $queryArray['must'] = [
                'multi_match' => [
                    'query' => $this->searchQuery,
                    'fields' => $this->searchFields
                ]
            ];
        } else {
            $queryArray = [
                "match_all" => []
            ];
        }

        $body['aggs'] = $this->agg($this->getFilterConfig());

        if(!$isAggregateQuery && count($this->selectedFilters) > 0) {

            $aggregateFilters = [];

            foreach($this->selectedFilters as $filterName => $filterGroup){

                $aggregateFilters['terms'] = [$filterName => array_values($filterGroup)];
            }

            $queryArray['should'] = $aggregateFilters;
        }

        $body['query']['bool'] = $queryArray;

        return $body;
    }

    protected function agg($aggregates)
    {
        $response = [];

        foreach($aggregates as $aggregate) {
            $field = $aggregate['name'];
            $response[$field] = [
                'terms' => ['field' => $field, 'size' => 6]
            ];
        }

        return $response;
    }

    /**
     * @param bool $paginated
     * @return \Illuminate\Support\Collection
     */
    protected function getCollection($paginated = true)
    {
        $result = new Result($this->client->search(
            $this->getParams(false, $paginated)), $this->getParams(false, $paginated)
        );

        $hits = $result->getHits();

        $collection = collect();

        foreach($hits as $hit){
            /**
             * @var Model $model
             */
            $model = $this->repository->instance();

            if(array_key_exists($this->primaryKey, $hit['_source'])){
                $model->id = $hit['_source'][$this->primaryKey];
                $model->forceFill($hit['_source']);

                $collection->push($model);
            }
        }

        $collection->amountOfHits = $result->getAmountOfHits();

        return $collection;
    }

    /**
     * @return array|\Pionect\PertwoRepositories\Product\Search\Elasticsearch\Facet[]
     */
    protected function searchForAggregates()
    {
        $result = new Result($this->client->search($this->getParams(true)), $this->getParams(true));

        return $result->getFacets();
    }
}

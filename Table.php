<?php

namespace Pionect\Backoffice\ContentElements\Tables;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Pionect\Backoffice\ContentElements\ContentContract;
use Pionect\Backoffice\Models\BaseRepository;

abstract class Table extends Fillable implements ContentContract, TableBuilderContract
{
    /**
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
        'id', 'columns', 'filters','name', 'type', 'searchQuery', 'from', 'size', 'currentPage', 'rows',
        'path', 'visibleColumns', 'selectedFilters', 'filtersVisible', 'paginator', 'total',
        'sortTargetOrder', 'sortColumnName',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'total' => 0, 'size' => 25, 'currentPage' => 1
    ];

    /**
     * @var array
     */
    protected $searchFields;

    /**
     * @var string
     */
    public $baseColumnClass;

    /**
     * @var string
     */
    public $baseFilterClass;

    /**
     * @var BaseRepository
     */
    protected $repository;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var TablePresenter
     */
    protected $tablePresenter;

    /**
     * @var LengthAwarePaginator
     */
    protected $paginator;

    /**
     * @var QueryCache
     */
    protected $queryCache;

    public function __construct(QueryCache $queryCache)
    {
        $this->queryCache = $queryCache;

        if($this->tablePresenter !== null) {
            $this->tablePresenter->setQueryCache($this->queryCache);
        }
    }

    /**
     * @param Request $request
     * @param $tableId
     * @param bool $loadingContent
     * @return $this|mixed
     */
    public function build(Request $request, $tableId, $loadingContent = false)
    {
        $jsonAllVisibleColumns = json_decode($request->cookie('visible_columns'), true);
        $visibleColumns = is_array($jsonAllVisibleColumns) && array_key_exists($tableId, $jsonAllVisibleColumns)
            ? $jsonAllVisibleColumns[$tableId] : null;

        $jsonAllSelectedFilters = json_decode($request->cookie('selected_filters'), true);
        $selectedFilters = is_array($jsonAllSelectedFilters)  && array_key_exists($tableId, $jsonAllSelectedFilters)
            ? $jsonAllSelectedFilters[$tableId] : null;

        $config = [
            'id' => $tableId,
            'type' => $tableId,
            'path' => $tableId,
            'sortColumnName' => request()->get('sortColumnName'),
            'sortTargetOrder' => request()->get('sortTargetOrder'),
            'searchQuery' => request()->get('search'),
            'currentPage' => request()->get('page'),
            'total' => request()->get('total'),
            'visibleColumns' => $visibleColumns,
            'selectedFilters' => $selectedFilters
        ];

        $this->fill($config);

        $this->buildColumns($config);

        if(!$loadingContent){
            $this->buildFilters($this->getFilterConfig(), $this->searchForAggregates());
        }

        return $this;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function render()
    {
        return view('backoffice::table.table', $this->attributes)->render();
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function renderContent()
    {
        $this->collection = $this->getCollection();

        $this->buildRows();

        $this->attributes['paginator'] = $this->paginator();

        return view('backoffice::table.table-content', $this->attributes)->render();
    }

    /**
     * @return array
     */
    public function getExportData()
    {
        $exportData = [];

        $this->collection = $this->getCollection(false);

        foreach ($this->collection as $entity) {

            $exportDataElement = [];

            foreach ($this->columns as $column) {
                /**
                 * @var Column $column
                 */
                if($column->visible){
                    $exportDataElement[$column->description] = $column->getExportValue($entity);
                }
            }

            $exportData[] = $exportDataElement;
        }

        return $exportData;
    }

    /**
     * @return Collection
     */
    protected function buildColumns($config)
    {
        $this->columns = collect();

        $sortQuery = [
            'field' => $config['sortColumnName'],
            'order' => $config['sortTargetOrder'],
        ];


        $queryParams = [
            'sort' => $sortQuery,
            'searchQuery' => $config['searchQuery']
        ];

        foreach($this->getColumnConfig() as $columnConfig){
            $columnConfig['visible'] = $this->columnVisible($columnConfig, $config);

            $this->columns->push($this->makeColumn($columnConfig, $queryParams));
        }

        return $this->columns;
    }

    /**
     * @param $config
     * @return mixed
     */
    protected function makeColumn($config, $queryParams)
    {
        if(array_key_exists('class', $config)){
            $column = new $config['class']($config, $queryParams);
        } else {
            $column = new $this->baseColumnClass($config, $queryParams);
        }

        return $column;
    }

    /**
     * Determining the visibility of the column:
     *
     * First check if the tables visible columns are configured (which is determined by the cookie).
     * Otherwise the 'default' config in the Table is checked.
     * Finally if none is defined the visibility reverts to false.
     *
     * @param $columnConfig
     * @param $tableConfig
     * @return bool
     */
    protected function columnVisible($columnConfig, $tableConfig)
    {
        if(array_key_exists('visibleColumns', $tableConfig) && $tableConfig['visibleColumns'] !== null) {
            $response = array_key_exists('name', $columnConfig)
                && in_array($columnConfig['name'], $tableConfig['visibleColumns']);
        } else {
            $response = array_key_exists('default', $columnConfig) ? $columnConfig['default']
                : false;
        }

        return $response;
    }

    /**
     * @param array $filterConfig
     * @param array $aggregates
     * @return Collection
     */
    protected function buildFilters(array $filterConfig, array $aggregates)
    {
        $this->filters = collect();

        foreach($filterConfig as $config)
        {
            if(array_key_exists('class', $config)){
                $filter = new $config['class']($config, $aggregates, $this->selectedFilters, $this->queryCache);
            } else {
                $filter = new $this->baseFilterClass($config, $aggregates, $this->selectedFilters, $this->queryCache);
            }

            $this->filters->push($filter);
        }

        return $this->filters;
    }

    /**
     * @description Build the Table.
     */
    protected function buildRows()
    {
        $this->rows = collect();

        foreach ($this->collection as $item) {
            $row = new Row($item, $this->columns->toArray(), $this->buildPath(), $this->tablePresenter, $this->getRowStylingClass($item));
            $this->rows->push($row);
        }
    }

    /**
     * @param null $item
     * @return string
     */
    public function getRowStylingClass($item = null)
    {
        return '';
    }

    /**
     * @description Validates sorting
     */
    protected function sorted()
    {
        if (is_array($this->sort)
            && array_key_exists('field', $this->sort)
            && array_key_exists('order', $this->sort)) {
            return true;
        }

        return false;
    }

    /**
     * @return LengthAwarePaginator
     */
    protected function paginator()
    {
        $total = $this->collection ? $this->collection->amountOfHits : 0;

        return new LengthAwarePaginator(
            $this->rows,
            $total,
            $this->size,
            $this->currentPage
        );
    }

    /**
     * @return float|int|null
     */
    public function pageFirstItem()
    {
        return $this->total > 0 ? ($this->currentPage - 1) * $this->size + 1 : null;
    }

    /**
     * @return string
     */
    protected function buildPath()
    {
        return '/' . $this->path;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param bool $paginated
     * @return mixed
     */
    abstract protected function getCollection($paginated = true);

    /**
     * @return array
     */
    abstract protected function getColumnConfig();

    /**
     * @return array
     */
    abstract protected function getFilterConfig();

    /**
     * @return array
     */
    abstract protected function searchForAggregates();
}

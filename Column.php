<?php

namespace Pionect\Backoffice\ContentElements\Tables;


use Pionect\Backoffice\Traits\ImportsProperties;

abstract class Column
{
    use ImportsProperties;

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $sortable = true;

    /**
     * @var bool
     */
    public $visible = true;

    /**
     * @var string
     */
    public $fieldName;

    /**
     * @var array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public $description;

    /**
     * @var string
     */
    public $targetOrder;

    /**
     * TODO: Remove ?
     */
    public $numeric;
    public $cellTemplate;

    /**
     * Column constructor.
     * @param array $config
     */
    public function __construct(array $config, $queryParams)
    {
        $this->import($config);

        $this->fieldName = $this->fieldName();

        $this->targetOrder = $this->targetOrder($queryParams);

        $this->description = trans('backoffice::table.' . $this->transKey());
    }

    protected function transKey()
    {
        return $this->name;
    }

    protected function targetOrder($queryParams)
    {
        if (!array_key_exists('sort', $queryParams) ||
            !is_array($queryParams['sort']) ||
            !array_key_exists('order', $queryParams['sort'])) {
            return 'asc';
        }

        if($queryParams['sort']['field'] !== $this->fieldName){
            return 'asc';
        }

        return ($queryParams['sort']['order'] === 'asc') ? 'desc' : 'asc';
    }

    /**
     * @return string
     */
    protected function fieldName()
    {
        return $this->name;
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getExportValue($entity)
    {
        $fieldName = $this->fieldName;

        return $entity->$fieldName;
    }
}

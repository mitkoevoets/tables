<?php namespace Pionect\Backoffice\ContentElements\Tables;

use Illuminate\Database\Eloquent\Model;
use Pionect\Backoffice\ContentElements\Tables\TablePresenter;

class Row
{
    public $id;
    public $cells;
    protected $item;
    protected $columns;
    public $url;
    protected $tablePresenter;
    public $stylingClassString;

    public function __construct(Model $item, array $columns, $path, TablePresenter $tablePresenter = null, string $stylingClassString = '')
    {
        $this->cells = collect();
        $this->item = $item;
        $this->url = $this->getUrl($path);
        $this->columns = $columns;
        $this->tablePresenter = $tablePresenter;
        $this->stylingClassString = $stylingClassString;

        $this->buildCells();
    }

    protected function buildCells()
    {
        foreach ($this->columns as $column) {
            $cell = new Cell();
            $cell->value = $this->getProperty($column);
            $cell->template = $column->cellTemplate;
            $cell->visible = $column->visible;
            $this->addCell($cell);
        }
    }

    protected function addCell(Cell $cell)
    {
        $this->cells->push($cell);
    }

    protected function getUrl($path)
    {
        return $path . '/' . $this->item->id;
    }

    /**
     * @param $column
     * @return mixed|string
     */
    protected function getProperty($column)
    {
        if($this->tablePresenter === null){
            return $this->item->{$column->name} ?? '';
        }

        return $this->tablePresenter->getProperty($this->item, $column->name) ?? '';
    }
}
<?php
namespace Pionect\Backoffice\ContentElements\Tables\Lead\Columns;

use Pionect\Backoffice\ContentElements\Tables\Column;

class NameColumn extends Column
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    protected function fieldName()
    {
        return 'lastname';
    }
}
<?php
namespace Pionect\Backoffice\ContentElements\Tables;

use Illuminate\Http\Request;

interface TableBuilderContract
{
    /**
     * @return string
     */
    public function render();

    /**
     * @param Request $request
     * @param $tableId
     * @return mixed
     */
    public function build(Request $request, $tableId);

    /**
     * @return string
     */
    public function renderContent();
}
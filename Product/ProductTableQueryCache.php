<?php
namespace Pionect\Backoffice\ContentElements\Tables\Product;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Pionect\Backoffice\ContentElements\Tables\QueryCache;

class ProductTableQueryCache extends QueryCache
{
    /**
     * @var Collection
     */
    public $productAttributeSets;

    /**
     * @var Collection
     */
    public $brands;

    /**
     * @var Collection
     */
    public $groups;

    public function __construct()
    {
        $this->productAttributeSets = $this->productAttributeSets();

        $this->brands = $this->brands();

        $this->groups = $this->groups();
    }

    protected function productAttributeSets()
    {
        return DB::table('productattributeset')->get();
    }

    protected function brands()
    {
        return DB::table('brand')->get();
    }

    protected function groups()
    {
        return DB::table('groups')->get();
    }
}

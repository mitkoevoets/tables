<?php
namespace Pionect\Backoffice\ContentElements\Tables\Order;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Pionect\Backoffice\ContentElements\Tables\QueryCache;

class OrderTableQueryCache extends QueryCache
{
    /**
     * @var Collection
     */
    public $tags;

    public function __construct()
    {
        $this->tags = $this->tags();
    }

    protected function tags()
    {
        return DB::table('tags')->get();
    }
}

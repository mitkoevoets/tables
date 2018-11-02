<?php

namespace Pionect\Backoffice\ContentElements\Tables\Product;


use Pionect\Backoffice\ContentElements\Tables\TablePresenter;
use Pionect\Backoffice\Models\Brand\Brand;
use Pionect\Backoffice\Models\Product\Group;
use Pionect\Backoffice\Models\Product\Product;

class ProductTablePresenter extends TablePresenter
{
    /**
     * @var ProductTableQueryCache
     */
    protected $queryCache;

    public function productattributeset_id(Product $product)
    {
        $productAttributeSetId = $product->productattributeset_id;

        if(!empty($productAttributeSetId)){
            $productAttributeSet = $this->queryCache->productAttributeSets->where('id', $productAttributeSetId)->first();

            return ($productAttributeSet !== null) ? $productAttributeSet->name : '';
        } else {
            return '';
        }
    }

    public function brand(Product $product)
    {
        $brandid = $product->brand;

        if(!empty($brandid)){
            $brand = $this->queryCache->brands->where('id', $brandid)->first();

            return ($brand !== null) ? $brand->name : '';
        } else {
            return '';
        }
    }

    public function group_id(Product $product)
    {
        $groupId = $product->group_id;

        if(!empty($groupId)){
            $group = $this->queryCache->groups->where('id', $groupId)->first();

            return ($group !== null) ? $group->name : '';
        } else {
            return '';
        }
    }

    public function variations(Product $product)
    {
        /**
         * Use indexed value instead of Model accessor
         */
        return $product->getAttributes()['variations'];
    }

    public function frontend_check(Product $product)
    {
        /**
         * Use indexed value instead of Model accessor
         */
        return $product->getAttributes()['frontend_check'];
    }
}

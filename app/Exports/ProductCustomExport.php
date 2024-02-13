<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ProductCustomExport implements FromCollection , WithHeadings
{
    protected $is_dummy = false;

    public function __construct($is_dummy)
    {
        $this->is_dummy = $is_dummy;
    }


    public function headings(): array
    {
        return
            $this->is_dummy ? [
                'name',
                'short_name',
                'slug',
                'thumb_image',
                'vendor_id',
                'category_id',
                'sub_category_id',
                'child_category_id',
                'brand_id',
                'qty',
                'weight',
                'short_description',
                'long_description',
                'video_link',
                'sku',
                'seo_title',
                'seo_description',
                'price',
                'offer_price'
            ] :
            [
                'id',
                'name',
                'short_name',
                'slug',
                'thumb_image',
                'vendor_id',
                'category_id',
                'sub_category_id',
                'child_category_id',
                'brand_id',
                'qty',
                'weight',
                'short_description',
                'long_description',
                'video_link',
                'sku',
                'seo_title',
                'seo_description',
                'price',
                'offer_price'
            ]
            ;
    }

    public function collection()
    {
        $first_item = Product::first();
        $first_id = $first_item ? $first_item->id : 0;
        return $this->is_dummy ? Product::select(
        'name',
        'short_name',
        'slug',
        'thumb_image',
        'vendor_id',
        'category_id',
        'sub_category_id',
        'child_category_id',
        'brand_id',
        'qty',
        'weight',
        'short_description',
        'long_description',
        'video_link',
        'sku',
        'seo_title',
        'seo_description',
        'price',
        'offer_price')->where('id', $first_id)->get() :

        Product::select('PID',
        'pro_name',
        'pro_name as short_name',
        'pro_name as slug',
        'pro_image',
        'supplier_id',
        'CID',
        'SCID',
        'newarraival as child_category_id',
        'BID',
        'stock',
        'newarraival as weight',
        'pro_desc as short_description',
        'pro_desc as long_description',
        'newarraival as video_link',
        'sku',
        'pro_name as seo_title',
        'pro_desc as seo_description',
        'pro_price',
        'pro_offer_price')->get();
    }
}

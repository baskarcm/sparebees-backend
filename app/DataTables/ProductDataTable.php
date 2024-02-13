<?php

namespace App\DataTables;

use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->editColumn('sn', function ($row) {
            return ++$_GET['start'];
        })
        ->editColumn('short_name', function ($row) {
            $setting = Setting::first();
            $frontend_url = $setting->frontend_url;
            $frontend_view = $frontend_url.'single-product?slug=';
            return '<a target="_blank" href="'.$frontend_view.''.$row->slug.'">'.$row->short_name.'</a>';
        })
        ->editColumn('thumb_image', function ($row) {
            return '<img class="rounded-circle" src="'.asset($row->thumb_image).'" alt="" width="100px" height="100px">';
        })
        ->editColumn('category_id', function ($row) {
            $type = '';
            if($row->new_product == 1)
            $type ='<span class="badge badge-primary p-1">'.__("admin.New").'</span>';
            elseif ($row->is_featured == 1)
            $type = '<span class="badge badge-success p-1">'.__("admin.Featured").'</span>';
            elseif ($row->is_top == 1)
            $type = '<span class="badge badge-warning p-1">'.__("admin.Top").'</span>';
            elseif ($row->is_best == 1)
            $type = '<span class="badge badge-danger p-1">'.__("admin.Best").'</span>';
            return $type;
        })
        ->editColumn('status', function ($row) {
            if($row->status == 1){
                return '<a href="javascript:;" onclick="changeProductStatus('.$row->id.')"><div class="toggle btn btn-success" data-toggle="toggle" role="button" style="width: 96.725px; height: 35.2px;">
                    <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="'.__("admin.Active").'" data-off="'.__("admin.InActive").'" data-onstyle="success" data-offstyle="danger">
                <div class="toggle-group"><label for="status_toggle" class="btn btn-success toggle-on">Active</label><label for="status_toggle" class="btn btn-danger toggle-off">InActive</label><span class="toggle-handle btn btn-light"></span></div></div></a>';
            }
            else{
                return '<a href="javascript:;" onclick="changeProductStatus('.$row->id.')"><div class="toggle btn btn-success" data-toggle="toggle" role="button" style="width: 96.725px; height: 35.2px;">
                    <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="'.__("admin.Active").'" data-off="'.__("admin.InActive").'" data-onstyle="success" data-offstyle="danger">
                <div class="toggle-group"><label for="status_toggle" class="btn btn-success toggle-off">Active</label><label for="status_toggle" class="btn btn-danger toggle-on">InActive</label><span class="toggle-handle btn btn-light"></span></div></div></a>';
            }
        })
        ->editColumn('action', function ($row) {
            $existOrder = OrderProduct::where('product_id',$row->id)->count();
            if ($existOrder == 0){
                $exists = '<a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData('. $row->id .')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            else{
                $exists = '<a href="javascript:;" data-toggle="modal" data-target="#canNotDeleteModal" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return '<a href="'. route("admin.product.edit",$row->id).'" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>'.$exists.
            '<div class="dropdown d-inline">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </button>

                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -131px, 0px);">
                        <a class="dropdown-item has-icon" href="'. route("admin.product-gallery",$row->id).'"><i class="far fa-image"></i> '.__('admin.Image Gallery').'</a>
                        <a class="dropdown-item has-icon" href="'. route('admin.product-variant',$row->id).'"><i class="fas fa-cog"></i>'.__('admin.Product Variant').'</a>
                    </div>
                    </div>';
        })
        ->rawColumns(['short_name','thumb_image','category_id','status','action'])
        ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('sn')->title('SN'),
            Column::make('short_name')->title('Name'),
            Column::make('price'),
            Column::make('thumb_image')->title('Photo'),
            Column::make('category_id')->title('Type'),
            Column::make('status'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}

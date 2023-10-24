@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Product Category')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Product Category')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Product Category')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.product-category.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th>{{__('admin.Id')}}</th>
                                    <th>{{__('admin.Name')}}</th>
                                    <th>{{__('admin.Image')}}</th>
                                    <th>{{__('admin.Icon')}}</th>
                                    <th>{{__('admin.Status')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                      <div class="modal-body">
                          {{__('admin.You can not delete this category. Because there are one or more sub categories or child categories or popular categories or home page three column categories or products has been created in this category.')}}
                      </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                </div>
            </div>
        </div>
    </div>

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/product-category/") }}'+"/"+id)
    }
    function changeProductCategoryStatus(id){
        var isDemo = "{{ env('APP_VERSION') }}"
        if(isDemo == 0){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/product-category-status/')}}"+"/"+id,
            success:function(response){
                toastr.success(response)
            },
            error:function(err){
                console.log(err);

            }
        })
    }
</script>
<link href="{{ asset('backend/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<script src="{{ asset('backend/dataTables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script src="{{ asset('backend/dataTables/dataTables.bootstrap4.js') }}"></script>

<script>
     var table = $('#dataTable').DataTable({
            // 'ordering': true,
            "processing": true,
            "serverSide": true,
            "pageLength": 20,
            "ajax": {
                "url": "{{route('admin.all-category.list')}}",
                "type":"get",
                data: function (d)
                {

                },
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            },
            "columns": [
            {
                "name":"id",
                "data":"id"
            },
            {
                "name":"name",
                "data":"name"
            },
            {
              "mRender": function (data, type, row) 
              {
                  return `<img src="{{(asset('/'))}}${row.image}" alt="" class="category_image">`;
              }
            },
            {
              "mRender": function (data, type, row) 
              {
                  return `<i class="${row.icon}"></i>`;
              }
            },
            {
              "mRender": function (data, type, row) 
              {
                if(row.status === 1)
                  return `<a href="javascript:;" onclick="changeProductCategoryStatus(${row.id})"> <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger"> </a>`
                else
                  return `<a href="javascript:;" onclick="changeProductCategoryStatus(${row.id})"> <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger"> </a>`
              }
            },
            {
              "mRender": function (data, type, row) 
              {
                if(row.sub_categories.length === 0 && row.products.length === 0)
                  return `<a href="/admin/product-category/${row.id}/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>  <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(${row.id})"><i class="fa fa-trash" aria-hidden="true"></i></a>`;
                else
                  return `<a href="/admin/product-category/${row.id}/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>  <a href="javascript:;" data-toggle="modal" data-target="#canNotDeleteModal" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i></a>`;
              }
            }
          
        ]
        });
</script>

@endsection

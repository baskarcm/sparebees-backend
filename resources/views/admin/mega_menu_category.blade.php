@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Mega Menu Category')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Mega Menu Category')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Mega Menu Category')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.mega-menu-category.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>

            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable1">
                            <thead>
                                <tr>
                                    <th>{{__('admin.Id')}}</th>
                                    <th>{{__('admin.Name')}}</th>
                                    <th>{{__('admin.Serial')}}</th>
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

    <link href="{{ asset('backend/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <script src="{{ asset('backend/dataTables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="{{ asset('backend/dataTables/dataTables.bootstrap4.js') }}"></script>
<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/mega-menu-category/") }}'+"/"+id)
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
            url:"{{url('/admin/mega-menu-category-status/')}}"+"/"+id,
            success:function(response){
                toastr.success(response)
            },
            error:function(err){
                console.log(err);

            }
        })
    }
    var table = $('#dataTable1').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{route('admin.mega-menu-category.list')}}",
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
                "name":"category.name",
                "data":"category.name"
            },
            {
                "name":"serial",
                "data":"serial"
            },
            {
               "mRender":function(data,type,row)
               {
                    if (row.status == 1)
                    return `<a href="javascript:;" onclick="changeProductCategoryStatus(${row.id})"> <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger"> </a>`
                    else 
                    return `<a href="javascript:;" onclick="changeProductCategoryStatus(${row.id})"> <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger"> </a>`
               }
            },
            {
               "mRender":function(data,type,row)
               {
                    return `<a href="/admin/mega-menu-category/${row.id}/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a> <a href="/admin/mega-menu-sub-category/${row.id}" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></a> <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(${row.id})"><i class="fa fa-trash" aria-hidden="true"></i></a>`
               }
            }
        ]
        });
</script>
@endsection

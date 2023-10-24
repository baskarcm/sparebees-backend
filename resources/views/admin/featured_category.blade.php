@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Featured Category')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{__('admin.Featured Category')}}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a>
                    </div>
                    <div class="breadcrumb-item">{{__('admin.aFeatured Category')}}</div>
                </div>
            </div>

            <div class="section-body d-none">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('admin.featured-category-banner') }}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf

                                    <div class="form-group">
                                        <label for="">{{__('admin.Existing Banner')}}</label>
                                        <div>
                                            <img class="sidebar_img" src="{{ asset($banner->featured_category_banner) }}" alt="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{__('admin.Featured Category Sidebar Banner')}}</label>
                                        <input required type="file" name="image" class="form-control-file">
                                    </div>

                                    <button class="btn btn-primary" type="submit">{{__('admin.Save')}}</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId">
                   <i class="fa fa-plus" aria-hidden="true"></i> {{__('admin.Add New')}}
                  </button>
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable1">
                                        <thead>
                                            <tr>
                                                <th>{{__('admin.Name')}}</th>
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
            </div>
        </section>
      </div>



      <!-- Modal -->
      <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">{{__('admin.Add featured category')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('admin.store-featured-category') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="">{{__('admin.Category')}}</label>
                                <select name="category_id" id="" class="form-control">
                                    <option value="">{{__('admin.Select')}}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="btn btn-primary" type="submit">{{__('admin.Save')}}</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
      </div>

    <link href="{{ asset('backend/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <script src="{{ asset('backend/dataTables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="{{ asset('backend/dataTables/dataTables.bootstrap4.js') }}"></script>
<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/destroy-featured-category/") }}'+"/"+id)
    }
    var table = $('#dataTable1').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{route('admin.all-featured-category.list')}}",
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
                "name":"category.name",
                "data":"category.name"
            },
            {
               "mRender":function(data,type,row)
               {
                    return `<a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(${row.id})"><i class="fa fa-trash" aria-hidden="true"></i></a>`
               }
            }
        ]
        });
</script>

@endsection

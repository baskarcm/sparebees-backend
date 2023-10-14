@extends('admin.master_layout')
@section('title')
<title>{{ $title }}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{ $title }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{ $title }}</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable1">
                            <thead>
                                <tr>
                                    {{-- <th width="5%">{{__('admin.SN')}}</th> --}}
                                    <th width="10%">{{__('admin.Customer')}}</th>
                                    <th width="10%">{{__('admin.Order Id')}}</th>
                                    <th width="10%">{{__('admin.Date')}}</th>
                                    <th width="10%">{{__('admin.Quantity')}}</th>
                                    <th width="10%">{{__('admin.Amount')}}</th>
                                    <th width="10%">{{__('admin.Order Status')}}</th>
                                    <th width="10%">{{__('admin.Payment')}}</th>
                                    <th width="15%">{{__('admin.Action')}}</th>
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
      @foreach ($orders as $index => $order)
      <div class="modal fade" id="orderModalId-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                      <div class="modal-header">
                              <h5 class="modal-title">{{__('admin.Order Status')}}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                          </div>
                  <div class="modal-body">
                      <div class="container-fluid">
                          <form action="{{ route('admin.update-order-status',$order->id) }}" method="POST">
                            @method('PUT')
                              @csrf
                              <div class="form-group">
                                  <label for="">{{__('admin.Payment')}}</label>
                                  <select name="payment_status" id="" class="form-control">
                                      <option {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                      <option {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Success')}}</option>
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="">{{__('admin.Order')}}</label>
                                  <select name="order_status" id="" class="form-control">
                                    <option {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                    <option {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('admin.In Progress')}}</option>
                                    <option {{ $order->order_status == 2 ? 'selected' : '' }}  value="2">{{__('admin.Delivered')}}</option>
                                    <option {{ $order->order_status == 3 ? 'selected' : '' }} value="3">{{__('admin.Completed')}}</option>
                                    <option {{ $order->order_status == 4 ? 'selected' : '' }} value="4">{{__('admin.Declined')}}</option>
                                  </select>
                              </div>


                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                      <button type="submit" class="btn btn-primary">{{__('admin.Update Status')}}</button>
                  </div>
                </form>
              </div>
          </div>
      </div>

      @endforeach


      <link href="{{ asset('backend/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" />
      <script src="{{ asset('backend/dataTables/jquery.dataTables.js') }}"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
  <script src="{{ asset('backend/dataTables/dataTables.bootstrap4.js') }}"></script>

<script>

    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/delete-order/") }}'+"/"+id)
    }

    var table = $('#dataTable1').DataTable({
            // 'ordering': true,
            "processing": true,
            "serverSide": true,

            "ajax": {
                "url": "{{route('admin.all-order.list')}}",
                "type":"get",
                data: function (d)
                {

                },
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            },
            "columns": [
            //     {
            //     "name":"sno",
            //     "data":"sno"
            // },
            {
                "name":"user.name",
                "data":"user.name"
            },
            {
                "name":"order_id",
                "data":"order_id"
            },
                       {
                "name":"created_at",
                "data":"created_at"
            },{
                "name":"product_qty",
                "data":"product_qty"
            },
            {
                "name":"total_amount",
                "data":"total_amount"
            },

           {
               "mRender":function(data,type,row)
               {
                    if (row.order_status == 1)
                    return ` <span class="badge badge-success">{{__('admin.Pregress')}} </span>`
                    else if (row.order_status == 2)
                    return `<span class="badge badge-success">{{__('admin.Delivered')}} </span>`
                    else if (row.order_status == 3)
                     return `<span class="badge badge-success">{{__('admin.Completed')}} </span>`
                    else if (row.order_status == 4)
                    return `<span class="badge badge-danger">{{__('admin.Declined')}} </span>`
                    else
                    return `<span class="badge badge-danger">{{__('admin.Pending')}}</span>`

               }
            },

           {
               "mRender":function(data,type,row)
               {
                    if(row.payment_status == 1)
                    return `<span class="badge badge-success">{{__('admin.success')}} </span>`
                    else
                    return `<span class="badge badge-danger">{{__('admin.Pending')}}</span>`
               }
            },
            {
                "mRender": function (data, type, row) {

                    return  ` <a href="/admin/order-show/${row.id}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>

<a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(${row.id})"><i class="fa fa-trash" aria-hidden="true"></i></a>

<a href="javascript:;" data-toggle="modal" data-target="#orderModalId-${row.id}" class="btn btn-warning btn-sm"><i class="fas fa-truck" aria-hidden="true"></i></a>`;

                }
            }
        ]
        });
</script>
@endsection

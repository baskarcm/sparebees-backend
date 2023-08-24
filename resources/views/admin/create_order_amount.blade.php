@extends('admin.master_layout')
@section('title')
<title>{{__('Create receive amount')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('Create order amount')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.delivery-man-order-amount') }}">{{__('Receive amount')}}</a></div>
              <div class="breadcrumb-item">{{__('Create Receive amount')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.delivery-man-order-amount') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('Receive amount')}}</a>
            <div class="row mt-4">
                <div class="col-8">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.delivery-man-order-amount.store') }}" method="POST" >
                            @csrf
                            <div class="row">

                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="delivery_man_id" id="delivery_man_id">
                                        <option value="">Select</option>
                                        @foreach ($deliveryMans as $deliveryman)
                                        <option value="{{ $deliveryman->id }}">{{ $deliveryman->fname }} {{ $deliveryman->lname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('Total Amount')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="total_amount" class="form-control"  name="total_amount" value="{{ old('total_amount') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
                <div class="col-4 d-none" id="method_des_box">
                  <div class="card">
                      <div class="card-body" id="method_des">

                      </div>
                  </div>
              </div>
          </div>
        </section>
      </div>

      <script>
        (function($) {
        "use strict";
        $(document).ready(function () {
            $("#delivery_man_id").on('change', function(){
                var manId = $(this).val();
                $.ajax({
                    type:"get",
                    url:"{{url('/admin/get-deliveryman-account-info/')}}"+"/"+manId,
                    success:function(response){
                       $("#method_des").html(response)
                       $("#method_des_box").removeClass('d-none')
                    },
                    error:function(err){}
                })
    
                if(!manId){
                    $("#method_des_box").addClass('d-none')
                }
    
            })
        });
    
        })(jQuery);
    </script>
@endsection

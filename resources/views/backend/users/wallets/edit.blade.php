@extends('backend.layouts.main_layout')
@section('title', $title)
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary box-borderless">
                <div class="box-header text-center with-border">
                    <h3 class="box-title font-weight-bold">
                        {{ __('Give :stockItem Amount', ['stockItem' => $wallet->stockItem->item]) }}
                    </h3>
                </div>
                <div class="box-body">
                    {!! Form::open(['route' => ['admin.users.wallets.update', 'id' => $wallet->user_id, 'walletId' => $wallet->id], 'method' => 'post', 'class'=>'form-horizontal validator']) !!}
                    {{ Form::hidden('base_key', base_key()) }}
                    {{--primary_balance--}}
                    <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                        <label for="amount" class="col-md-4 control-label required">{{ __('Amount') }}</label>
                        <div class="col-md-8">
                            {{ Form::text('amount',  old('amount', null), ['class'=>'form-control', 'id' => 'amount','data-cval-name' => 'The amount field','data-cval-rules' => 'required|numeric|escapeInput|between:0.00000001, 99999999999.99999999', 'placeholder' => __('ex: 0.00000001')]) }}
                            <span class="validation-message cval-error" data-cval-error="{{ fake_field('amount') }}">{{ $errors->first('amount') }}</span>
                        </div>
                    </div>

                    {{--submit button--}}
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            {{ Form::submit(__('Give Amount'),['class'=>'btn btn-success form-submission-button']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('common/vendors/cvalidator/cvalidator.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.validator').cValidate({});
        });
    </script>
@endsection
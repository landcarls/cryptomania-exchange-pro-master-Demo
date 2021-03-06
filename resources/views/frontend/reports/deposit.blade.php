@extends('backend.layouts.main_layout')
@section('title', $title)
@section('content')
    <h3 class="page-header">{{ __('Deposits of :itemName', ['itemName' => $wallet->stockItem->item]) }}</h3>
    {!! $list['filters'] !!}

    <div class="row">
        <div class="col-lg-12">
            @include('frontend.reports._payment_nav', ['routeName' => 'reports.trader.deposits', 'walletId' => $wallet->id])
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <table class="table datatable dt-responsive display nowrap dc-table" style="width:100% !important;">
                        <thead>
                        <tr>
                            <th class="min-desktop">{{ __('Ref ID') }}</th>
                            <th class="all">{{ __('Amount') }}</th>
                            @if(!$status)
                            <th class="all">{{ __('Status') }}</th>
                            @endif
                            <th class="none">{{ __('Address') }}</th>
                            <th class="none">{{ __('Txn Id') }}</th>
                            <th class="min-desktop">{{ __('Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list['query'] as $transaction)
                            <tr>
                                <td>{{ $transaction->ref_id }}</td>
                                <td>{{ $transaction->amount }} <span class="strong">{{ $transaction->item }}</span></td>
                                @if(!$status)
                                <td>
                                    <span class="label label-{{ config('commonconfig.payment_status.' . $transaction->status . '.color_class') }}">{{ payment_status($transaction->status) }}
                                    </span>
                                </td>
                                @endif
                                <td>{{ $transaction->address }}</td>
                                <td>{{ $transaction->txn_id }}</td>
                                <td>{{ $transaction->created_at->toFormattedDateString() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {!! $list['pagination'] !!}
@endsection

@section('script')
    <!-- for datatable and date picker -->
    <script src="{{ asset('common/vendors/datepicker/datepicker.js') }}"></script>
    <script src="{{asset('common/vendors/datatable_responsive/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('common/vendors/datatable_responsive/datatables/plugins/bootstrap/datatables.bootstrap.js')}}"></script>
    <script src="{{asset('common/vendors/datatable_responsive/table-datatables-responsive.js')}}"></script>
    <script type="text/javascript">
        //Init jquery Date Picker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            orientation: 'bottom',
            todayHighlight: true,
        });
    </script>
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom full-in-small" style="cursor: move;">
            <ul class="nav nav-tabs ui-sortable-handle">
                <li class="active">
                    <a href="#pills-buy-elunium" data-toggle="tab" aria-expanded="false">{{ __('BUY ELUNIUM') }}</a>
                </li>
                
                <li class="">
                    <a href="#pills-stop-limit" data-toggle="tab" aria-expanded="true">{{ __('STOP LIMIT') }}</a>
                </li>
                
                <li class="">
                        <a href="#pills-sell-elunium" data-toggle="tab" aria-expanded="true">{{ __('SELL ELUNIUM') }}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="pills-buy-elunium">
                    @include('frontend.exchange.buy_form')
                </div>
                
                <div class="tab-pane" id="pills-stop-limit">
                    @include('frontend.exchange.stop_limit_form')
                </div>

                <div class="tab-pane" id="pills-sell-elunium">
                    @include('frontend.exchange.sell_form')
                </div>
            </div>
        </div>
    </div>
</div>
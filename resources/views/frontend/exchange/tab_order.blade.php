<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom full-in-small" style="cursor: move;">
        <thead>
            <ul class="nav nav-tabs ui-sortable-handle">
            @auth
                <li class="active">
                    <a href="#my-order" data-toggle="tab" aria-expanded="false">{{ __('MY ORDERS') }}</a>
                </li>
            @endauth
            </ul>
        </thead>
            <div class="tab-content">
                @auth
                    <div class="tab-pane active" id="my-order">
                        @include('frontend.exchange.my_order')
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
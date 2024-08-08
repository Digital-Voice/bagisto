<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.carts.view.title', ['cart_id' => $cart->id])
    </x-slot>

    <!-- Header -->
    <div class="grid">
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            {!! view_render_event('bagisto.admin.sales.cart.title.before', ['cart' => $cart]) !!}

            <div class="flex items-center gap-2.5">
                <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                    @lang('admin::app.sales.carts.view.title', ['cart_id' => $cart->id])
                </p>
            </div>

            {!! view_render_event('bagisto.admin.sales.cart.title.after', ['cart' => $cart]) !!}

            <!-- Back Button -->
            <a
                href="{{ route('admin.sales.carts.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            >
                @lang('admin::app.account.edit.back-btn')
            </a>
        </div>
    </div>

        <!-- Cart details -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Component -->
            <div class="flex flex-col flex-1 gap-2 max-xl:flex-auto">
                {!! view_render_event('bagisto.admin.sales.cart.left_component.before', ['cart' => $cart]) !!}

                <div class="bg-white rounded box-shadow dark:bg-gray-900">
                    <div class="flex justify-between p-4">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('Cart Items') ({{ count($cart->items) }})
                        </p>

                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.sales.orders.view.grand-total', ['grand_total' => core()->formatBasePrice($cart->base_grand_total)])
                        </p>
                    </div>

                    <!-- Cart items -->
                    <div class="grid">
                        @foreach ($cart->items as $item)
                            {!! view_render_event('bagisto.admin.sales.cart.list.before', ['cart' => $cart]) !!}

                            <div class="flex justify-between gap-2.5 border-b border-slate-300 px-4 py-6 dark:border-gray-800">
                                <div class="flex gap-2.5">
                                    @if($item?->product?->base_image_url)
                                        <img
                                            class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded"
                                            src="{{ $item?->product->base_image_url }}"
                                        >
                                    @else
                                        <div class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">

                                            <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                                @lang('admin::app.sales.invoices.view.product-image')
                                            </p>
                                        </div>
                                    @endif

                                    <div class="grid place-content-start gap-1.5">
                                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                                            {{ $item->name }}
                                        </p>

                                        <div class="flex flex-col place-items-start gap-1.5">
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.amount-per-unit', [
                                                    'amount' => core()->formatBasePrice($item->base_price),
                                                    'qty'    => $item->qty_ordered,
                                                ])
                                            </p>

                                            @if (isset($item->additional['attributes']))
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    @foreach ($item->additional['attributes'] as $attribute)
                                                        {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                    @endforeach
                                                </p>
                                            @endif

                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.sku', ['sku' => $item->sku])
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                {{ $item->qty_ordered ? trans('admin::app.sales.orders.view.item-ordered', ['qty_ordered' => $item->qty_ordered]) : '' }}

                                                {{ $item->qty_invoiced ? trans('admin::app.sales.orders.view.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}

                                                {{ $item->qty_shipped ? trans('admin::app.sales.orders.view.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}

                                                {{ $item->qty_refunded ? trans('admin::app.sales.orders.view.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}

                                                {{ $item->qty_canceled ? trans('admin::app.sales.orders.view.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-1 place-content-start">
                                    <div class="">
                                        <p class="flex items-center justify-end text-base font-semibold text-gray-800 gap-x-1 dark:text-white">
                                            {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col place-items-start items-end gap-1.5">
                                        @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.price', ['price' => core()->formatBasePrice($item->base_price_incl_tax)])
                                            </p>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.price-excl-tax', ['price' => core()->formatBasePrice($item->base_price)])
                                            </p>
                                            
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.price-incl-tax', ['price' => core()->formatBasePrice($item->base_price_incl_tax)])
                                            </p>
                                        @else
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.price', ['price' => core()->formatBasePrice($item->base_price)])
                                            </p>
                                        @endif

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.orders.view.tax', [
                                                'percent' => number_format($item->tax_percent, 2) . '%',
                                                'tax'     => core()->formatBasePrice($item->base_tax_amount)
                                            ])
                                        </p>

                                        @if ($cart->base_discount_amount > 0)
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.discount', ['discount' => core()->formatBasePrice($item->base_discount_amount)])
                                            </p>
                                        @endif

                                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.sub-total', ['sub_total' => core()->formatBasePrice($item->base_total_incl_tax)])
                                            </p>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.sub-total-excl-tax', ['sub_total' => core()->formatBasePrice($item->base_total)])
                                            </p>
                                            
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.sub-total-incl-tax', ['sub_total' => core()->formatBasePrice($item->base_total_incl_tax)])
                                            </p>
                                        @else
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.sub-total', ['sub_total' => core()->formatBasePrice($item->base_total)])
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.admin.sales.cart.list.after', ['cart' => $cart]) !!}
                        @endforeach
                    </div>

                    <div class="mt-4 flex w-full justify-end gap-2.5 p-4">
                        <div class="flex flex-col gap-y-1.5">
                            @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.summary-sub-total-excl-tax')
                                </p>
                                
                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.summary-sub-total-incl-tax')
                                </p>
                            @else
                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.summary-sub-total')
                                </p>
                            @endif

                            @if ($haveStockableItems = $cart->haveStockableItems())
                                @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                    <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.orders.view.shipping-and-handling-excl-tax')
                                    </p>
                                    
                                    <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.orders.view.shipping-and-handling-incl-tax')
                                    </p>
                                @else
                                    <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.orders.view.shipping-and-handling')
                                    </p>
                                @endif
                            @endif

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.summary-tax')
                            </p>

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.summary-discount')
                            </p>

                            <p class="text-base font-semibold !leading-5 text-gray-800 dark:text-white">
                                @lang('admin::app.sales.orders.view.summary-grand-total')
                            </p>

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.total-paid')
                            </p>

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.total-refund')
                            </p>

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.total-due')
                            </p>
                        </div>

                        <div class="flex flex-col gap-y-1.5">
                            @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($cart->base_sub_total) }}
                                </p>
                            @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($cart->base_sub_total) }}
                                </p>
                                
                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($cart->base_sub_total_incl_tax) }}
                                </p>
                            @else
                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($cart->base_sub_total) }}
                                </p>
                            @endif

                            @if ($haveStockableItems)
                                @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                    <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                        {{ core()->formatBasePrice($cart->base_shipping_amount_incl_tax) }}
                                    </p>
                                @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                    <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                        {{ core()->formatBasePrice($cart->base_shipping_amount) }}
                                    </p>
                                    
                                    <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                        {{ core()->formatBasePrice($cart->base_shipping_amount_incl_tax) }}
                                    </p>
                                @else
                                    <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                        {{ core()->formatBasePrice($cart->base_shipping_amount) }}
                                    </p>
                                @endif
                            @endif

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($cart->base_tax_amount) }}
                            </p>

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($cart->base_discount_amount) }}
                            </p>

                            <p class="text-base font-semibold !leading-5 text-gray-800 dark:text-white">
                                {{ core()->formatBasePrice($cart->base_grand_total) }}
                            </p>

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($cart->base_grand_total_invoiced) }}
                            </p>

                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($cart->base_grand_total_refunded) }}
                            </p>

                            @if($cart->status !== 'canceled')
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($cart->base_total_due) }}
                                </p>
                            @else
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice(0.00) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.sales.cart.left_component.after', ['cart' => $cart]) !!}
            </div>

            <!-- Right Component -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                {!! view_render_event('bagisto.admin.sales.cart.right_component.before', ['cart' => $cart]) !!}

                <!-- Customer and address information -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.orders.view.customer')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="{{ $cart->billing_address ? 'pb-4' : '' }}">
                            <div class="flex flex-col gap-1.5">
                                <p class="font-semibold text-gray-800 dark:text-white">
                                    {{ $cart->customer_full_name }}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.cart.customer_full_name.after', ['cart' => $cart]) !!}

                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $cart->customer_phone }}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.cart.customer_phone.after', ['cart' => $cart]) !!}

                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.customer-group') : {{ $cart->is_guest ? core()->getGuestCustomerGroup()?->name : ($cart->customer->group->name ?? '') }}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.cart.customer_group.after', ['cart' => $cart]) !!}
                            </div>
                        </div>

                        <!-- Billing Address -->
                        @if ($cart->billing_address)
                            <span class="block w-full border-b dark:border-gray-800"></span>

                            <div class="{{ $cart->shipping_address ? 'pb-4' : '' }}">

                                <div class="flex items-center justify-between">
                                    <p class="py-4 text-base font-semibold text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.orders.view.billing-address')
                                    </p>
                                </div>

                                @include ('admin::sales.address', ['address' => $cart->billing_address])

                                {!! view_render_event('bagisto.admin.sales.cart.billing_address.after', ['cart' => $cart]) !!}
                            </div>
                        @endif

                        <!-- Shipping Address -->
                        @if ($cart->shipping_address)
                            <span class="block w-full border-b dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="py-4 text-base font-semibold text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.shipping-address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $cart->shipping_address])

                            {!! view_render_event('bagisto.admin.sales.cart.shipping_address.after', ['cart' => $cart]) !!}
                        @endif
                    </x-slot>
                </x-admin::accordion>

                <!-- Cart Information -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.carts.view.cart-information')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="flex justify-start w-full gap-5">
                            <div class="flex flex-col gap-y-1.5">
                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.carts.view.cart-date')
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.channel')
                                </p>
                            </div>

                            <div class="flex flex-col gap-y-1.5">
                                {!! view_render_event('bagisto.admin.sales.cart.created_at.before', ['cart' => $cart]) !!}

                                <!-- Cart Date -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{core()->formatDate($cart->created_at) }}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.cart.created_at.after', ['cart' => $cart]) !!}

                                <!-- Cart Channel -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{$cart->channel->name}}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.cart.channel_name.after', ['cart' => $cart]) !!}
                            </div>
                        </div>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.sales.cart.right_component.after', ['cart' => $cart]) !!}
            </div>
        </div>
    </div>
</x-admin::layouts>

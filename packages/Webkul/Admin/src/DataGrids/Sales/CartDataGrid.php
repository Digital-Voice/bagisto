<?php

namespace Webkul\Admin\DataGrids\Sales;

use Illuminate\Support\Facades\DB;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Repositories\OrderRepository;

class CartDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart')
            ->distinct()
            ->leftJoin('channel_translations', function ($leftJoin) {
                $leftJoin->on('cart.channel_id', '=', 'channel_translations.channel_id')
                    ->where('channel_translations.locale', app()->getLocale());
            })
            ->select(
                'cart.id',
                'cart.base_grand_total',
                'cart.created_at',
                'cart.channel_id',
                'cart.customer_phone',
                'channel_translations.name as channel_name',
                DB::raw('CONCAT('.DB::getTablePrefix().'cart.customer_first_name, " ", '.DB::getTablePrefix().'cart.customer_last_name) as full_name'),
            );

        $this->addFilter('full_name', DB::raw('CONCAT('.DB::getTablePrefix().'cart.customer_first_name, " ", '.DB::getTablePrefix().'cart.customer_last_name)'));
        $this->addFilter('created_at', 'cart.created_at');
        $this->addFilter('channel_id', 'cart.channel_id');
        $this->addFilter('id', 'cart.id');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.sales.carts.index.datagrid.cart-id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.sales.orders.index.datagrid.grand-total'),
            'type'       => 'string',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'channel_id',
            'label'              => trans('admin::app.sales.orders.index.datagrid.channel-name'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => core()->getAllChannels()
                ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                ->values()
                ->toArray(),
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('admin::app.sales.orders.index.datagrid.customer'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        /**
         * Searchable dropdown sample. In testing phase.
         */
        $this->addColumn([
            'index'      => 'customer_phone',
            'label'      => trans('admin::app.sales.carts.index.datagrid.phone'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'items',
            'label'      => trans('admin::app.sales.orders.index.datagrid.items'),
            'type'       => 'string',
            'exportable' => false,
            'closure'    => function ($value) {
                $cart = app(CartRepository::class)->with('items')->find($value->id);

                return view('admin::sales.carts.items', compact('cart'))->render();
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.sales.orders.index.datagrid.date'),
            'type'            => 'date',
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('sales.carts.view')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.sales.orders.index.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.carts.view', $row->id);
                },
            ]);
        }
    }
}

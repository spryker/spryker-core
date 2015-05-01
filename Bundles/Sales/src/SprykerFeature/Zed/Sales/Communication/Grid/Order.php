<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid;

class Order
{

    const GRID_ID = 'order-grid';

    /**
     * @param \SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection
     */
    public function defineColumns(\SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection)
    {
        $columnCollection->addColumn('details')
            ->title(__(' '))
            ->width(35)
            ->filterable(false)
            ->sortable(false)
            ->persist(false)
            ->template('
                <a href="/sales/order-details/index?id=${id_sales_order}">
                    <i class="icon-eye-open"></i>
                </a>
            ');

        $columnCollection->addColumnId('id_sales_order')
            ->title(__('#'))
            ->hidden(true);

        $columnCollection->addColumnString('increment_id')
            ->title(__('Order Number'));

        $columnCollection->addColumnString('shipping_firstname')
            ->title(__('First Name'));

        $columnCollection->addColumnString('shipping_lastname')
            ->title(__('Last Name'));

        $columnCollection->addColumnNumber('grand_total')
            ->format('{0:c}')
            ->title(__('Total Order'));

        $columnCollection->addColumnString('process_name')
            ->filterable(false)
            ->title(__('Process'))
            ->hidden(true);

        $columnCollection->addColumnString('payment_provider')
            ->title(__('Payment Provider'))
            ->hidden(true);

        $columnCollection->addColumnString('payment_method')
            ->title(__('Payment Method'));

        $columnCollection->addColumnString('status')
            ->filterable(false)
            ->title(__('Status'));

        $columnCollection->addColumnString('is_test')
            ->title(__('Is Test Order'))
            ->template('<i class="${is_test}"></i>');

        $columnCollection->addColumnDate('updated_at')
            ->title(__('Last Update'))
            ->template('#= kendo.toString(updated_at, "yyyy-MM-dd HH:mm:ss") #')
            ->filterable(false);

        $columnCollection->addColumnString('message')
            ->title(__('Last Comment'))
            ->sortable(false);
    }

    public function configureGrid(Grid $grid)
    {
        $grid->columnMenu(true);
    }

}

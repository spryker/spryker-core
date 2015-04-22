<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid;

class Items
{

    const GRID_ID = 'items-grid';

    /**
     * @param \SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection
     */
    public function defineColumns(\SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection)
    {
        $columnCollection->addColumnId('id_sales_order_item')
            ->title(__('#'));

        $columnCollection->addColumnNumber('fk_sales_order')
            ->title(__('Order'))
            ->template('<a href="/sales/order-details/index?id=#= fk_sales_order #">' . __('Show order details') . '</a>');

        $columnCollection->addColumnString('sku')
            ->title(__('Sku'));

        $columnCollection->addColumnString('name')
            ->title(__('Name'));

        $columnCollection->addColumnNumber('item_fk_sales_order_process')
            ->title(__('Process'))
            ->values($this->getProcessOptions());

        $columnCollection->addColumnNumber('FK_OMS_ORDER_ITEM_STATUS')
            ->title(__('Status'))
            ->values($this->getStatusOptions());

        $columnCollection->addColumnNumber('is_test')
            ->title(__('Is Test'))
            ->values($this->getIsTestOptions());

        $columnCollection->addColumnDate('last_status_change')
            ->title(__('Last Status Change'))
            ->template('#= kendo.toString(last_status_change, "dd.MM.yyyy") #') // TODO centralize to store configuration
            ->filterable(false); // TODO filter does not work, because created_at is ambiguous and Kendo searches for exact time, incl minute

        $columnCollection->addColumnDate('created_at')
            ->title(__('Created Date'))
            ->template('#= kendo.toString(created_at, "dd.MM.yyyy") #') // TODO centralize to store configuration
            ->filterable(false); // TODO filter does not work, because created_at is ambiguous and Kendo searches for exact time, incl minute

        $columnCollection->addColumnDate('created_at')
            ->title(__('Created Time'))
            ->template('#= kendo.toString(created_at, "HH:mm") #') // TODO centralize to store configuration
            ->filterable(false); // TODO filter does not work, because created_at is ambiguous and Kendo searches for exact time, incl minute
    }

    /**
     * @param Grid $grid
     */
    public function configureGrid(Grid $grid)
    {
        $grid->columnMenu(true);
    }

    /**
     * @return array
     */
    protected function getProcessOptions()
    {
        $options = array();
        $collection = \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery::create()->find();
        /* @var \SprykerFeature\Zed\Catalog\Persistence\Propel\PacCatalogAttributeSet $attributeSet */
        foreach ($collection as $item) {
            $options[] = ['value' => $item->getPrimaryKey(), 'text' => $item->getName()];
        }

        return $options;
    }

    /**
     * @return array
     */
    protected function getStatusOptions()
    {
        $options = [];
        $collection = \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatusQuery::create()->find();
        /* @var \SprykerFeature\Zed\Catalog\Persistence\Propel\PacCatalogAttributeSet $attributeSet */
        foreach ($collection as $item) {
            $options[] = ['value' => $item->getPrimaryKey(), 'text' => $item->getName()];
        }

        return $options;
    }

    /**
     * @return array
     */
    protected function getIsTestOptions()
    {
        return [
            ['value' => 0, 'text' => 'Real'],
            ['value' => 1, 'text' => 'Test']
        ];
    }

}

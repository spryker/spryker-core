<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid;

class Matrix
{

    const GRID_ID = 'matrix-grid';

    /** @var bool */
    protected $defaultServerSorting = false;

    /** @var int */
    protected $pageSize = 10000;

    /** @var \SprykerFeature\Zed\Sales\Communication\Grid\Matrix\DataSource */
    protected $dataSource;

    /**
     * @param Grid $grid
     */
    public function configureGrid(Grid $grid)
    {
        $grid->editable(false)
            ->pageable(false)
            ->columnMenu(true)
            ->groupable(false);
    }

    /**
     * @param \SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection
     */
    public function defineColumns(\SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection)
    {
        $columnCollection->addColumnId('id_sales_order_item')
            ->title(__('#'))
            ->hidden(true);

        $columnCollection->addColumnString('status_name')
            ->title(__('Status Name'))
            ->sortable(true);

        $orderProcessNames = $this->dataSource->getAllOrderProcessNames();
        foreach ($orderProcessNames as $orderProcessName) {
            $colId = str_replace('-', '_', $orderProcessName);
            $colId = preg_replace('/\s+/', '_', $colId);
            $colId = preg_replace('/\(|\)/', '_', $colId);
            $columnCollection->addColumnString($colId)
                ->title(__(ucwords($orderProcessName)))
                ->template("#= " . ($colId) . " #")
                ->sortable(false)
                ->filterable(false);
        }
    }

}

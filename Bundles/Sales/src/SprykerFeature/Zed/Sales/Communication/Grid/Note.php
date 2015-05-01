<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid;

class Note
{

    const GRID_ID = 'note-grid';

    /**
     * @param \SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection
     */
    public function defineColumns(\SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection)
    {
        $columnCollection->addColumnId('id_sales_order_note')
            ->hidden(true)
            ->title(__('#'));

        $columnCollection->addColumnBooleanSuccess('success');

        $columnCollection->addColumnString('message')
            ->title(__('Message'));

        $columnCollection->addColumnNumber('fk_acl_user')
            ->setEditable(false)
            ->width(220)
            ->values($this->getDataSource()->getAclUserOptions())
            ->title(__('User'));

        $columnCollection->addColumnString('command')
            ->setEditable(false)
            ->hidden(true)
            ->title(__('Command'));

        $columnCollection->addColumnDate('created_at')
            ->title(__('Created'))
            ->width(180)
            ->template('#= kendo.toString(created_at, "dd.MM.yyyy") #')
            ->filterable(false);
    }

    /**
     * @param $gridBuilder
     */
    public function configureGridBuilder($gridBuilder)
    {
        $gridBuilder->setPageSize(5);
        $gridBuilder->setSelectablePageSizes(array(5, 10, 20, 50, 100));
    }

    /**
     * @param Grid $grid
     */
    public function configureGrid(Grid $grid)
    {
        $grid->editable(false)
            ->pageable(false)
            ->groupable(false)
            ->columnMenu(true);
    }

}

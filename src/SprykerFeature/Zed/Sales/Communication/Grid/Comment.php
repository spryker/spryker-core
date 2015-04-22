<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid;

class Comment
{

    const GRID_ID = 'comment-grid';

    /**
     * @param \SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection
     */
    public function defineColumns(\SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection)
    {
        $columnCollection->addColumnId('id_sales_order_comment')
            ->hidden(true)
            ->title(__('#'));

        $columnCollection->addColumnString('username')
            ->setEditable(false)
            ->width(220)
            ->title(__('User'));

        $columnCollection->addColumnString('message')
            //->setEditable(false)
            ->title(__('Message'));

        $columnCollection->addColumnDate('created_at')
            ->title(__('Created'))
            ->width(180)
            ->setEditable(false)
            ->persist(false)
            ->template('#= kendo.toString(created_at, "yyyy-MM-dd HH:mm:ss") #')
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
        $grid->editable('inline')
            ->columnMenu(true)
            ->pageable(false)
            ->groupable(false);
        $this->addToolbarItems($grid);
    }

    /**
     * @param Grid $grid
     */
    protected function addToolbarItems(Grid $grid)
    {
        $addRecord = new \Kendo\UI\GridToolbarItem('create');
        $addRecord->text(__('Add Message'));
        $saveChanges = new \Kendo\UI\GridToolbarItem('save');
        $saveChanges->text(__('Save Changes'));
        $cancelChanges = new \Kendo\UI\GridToolbarItem('cancel');
        $cancelChanges->text(__('Cancel Changes'));
        $grid->addToolbarItem($addRecord, $saveChanges, $cancelChanges);
    }

}

<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Grid;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Business\Model\Salesrule;

class Condition
{

    const GRID_ID = 'condition-grid';

    /**
     * @param \SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection
     */
    public function defineColumns(\SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection)
    {
        $idSalesRule = Request::createFromGlobals()->query->get(Salesrule::ID_SALES_RULE_URL_PARAMETER);

        $columnCollection->addColumnId('id_salesrule_condition')->title(__('ID'))->hidden(true);

        $columnCollection->addColumn('details')
            ->title(__(' '))
            ->width(30)
            ->filterable(false)
            ->sortable(false)
            ->persist(false)
            ->template(
                '<a class="icon-eye-open" data-ajax-edit-condition="\#sales-rule-condition-form" href="/salesrule/condition-ajax/index?id-sales-rule-condition=${id_salesrule_condition}'
                . Salesrule::ID_SALES_RULE_URL_PARAMETER
                . '/' . $idSalesRule
                . '"></a>'
            );

        $columnCollection->addColumnString('condition')
            ->title(__('Condition'));

        $columnCollection->addColumnString('configuration')
            ->title(__('Description')
            )->encoded(false);

        $columnCollection->addColumn('control')->title(' ')
            ->persist(false)
            ->addCommandItem(['className' => 'icon-trash', 'name' => 'destroy', 'text' => '']);
    }

    /**
     * @param Grid $grid
     */
    public function configureGrid(Grid $grid)
    {
        $grid->groupable(false)
            ->resizable(true)
            ->columnMenu(true)
            ->pageable([
                'input' => true,
                'numeric' => false,
                'pageSizes' => true,
            ])
            ->editable('popup');
    }

}

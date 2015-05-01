<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Grid;

use SprykerFeature\Zed\Salesrule\Communication\Grid\Salesrule\DataSource;
use SprykerFeature\Zed\Salesrule\Business\Model\Salesrule as SalesRuleModel;

class Salesrule
{

    const GRID_ID = 'salesrule-grid';

    /**
     * @param \SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection
     */
    public function defineColumns(\SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection)
    {
        $columnCollection->addColumnId('id_salesrule')
            ->title(__(' '))
            ->width(35)
            ->filterable(false)
            ->sortable(false)
            ->persist(false)
            ->template('<a href="/salesrule/index/edit?' . SalesRuleModel::ID_SALES_RULE_URL_PARAMETER . '=${id_salesrule}"><i class="icon-eye-open"></i></a>');

        $columnCollection->addColumnString('id_salesrule')
            ->title(__('ID'))
            ->width(70)
            ->template('${id_salesrule}');

        $columnCollection->addColumnString('name')
            ->title('Name')
            ->template('${name}');

        $columnCollection->addColumnString('description')
            ->title('Description');

        $columnCollection->addColumnString(DataSource::DATA_KEY_SALES_RULE_CONDITION)
            ->sortable(false)
            ->filterable(false)
            ->title(__('Time'))
            ->template('<i class="${sales_rule_expiration_status}"></i> ${sales_rule_condition}')
            ->width('200px');

        $columnCollection->addColumnString(DataSource::DATA_KEY_SALES_RULE_CODE_POOL_NAME)
            ->sortable(false)
            ->filterable(false)
            ->title(__('Code Groups'))
            ->template('${sales_rule_code_pool_name}')
            ->width('200px');

        $columnCollection->addColumnString('is_active')
            ->width('70px')
            ->title(__('Active'))
            ->template('<i class="${is_active}"></i>');

        $columnCollection->addColumnDate('updated_at')
            ->title('Last Update')
            ->width('150px')
            ->template('#= kendo.toString(updated_at, "yyyy-MM-dd HH:mm:ss") #')
            ->filterable(false);

        $columnCollection->addColumnString('sales_rule_expiration_status')
            ->persist(false)
            ->hidden(true);
    }
}

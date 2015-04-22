<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Grid;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Business\Model\Codepool;

class Code
{

    const GRID_ID = 'code-grid';

    /**
     * @param \SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection
     */
    public function defineColumns(\SprykerFeature_Zed_Library_Gui_Grid_ColumnCollection $columnCollection)
    {
        $columnCollection->addColumnId('id_salesrule_code')
            ->title(__('#'))
            ->width('70px');

        $columnCollection->addColumnString('code')
            ->title(__('Code'));

        $columnCollection->addColumnBoolean('is_active')
            ->title(__('Active'))
            ->template('<i class="${active_sign}"></i>')
            ->width('70px');

        if (!$this->checkIfIsReusable()) {

            $columnCollection->addColumnString('order_id')
                ->title(__('Order Id'))
                ->hidden(true);

            $columnCollection->addColumnString('redeeming_order')
                ->title(__('Redeeming Order'))
                ->template('<a href="/sales/order-details/index?id=${order_id}" target="_blank">${redeeming_order}</a>');
        }

        $columnCollection->addColumn('control')
            ->title(' ')
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

    /**
     * @return bool
     */
    protected function checkIfIsReusable()
    {
        $request = Request::createFromGlobals();
        $request->query->get(Codepool::ID_CODE_POOL_URL_PARAMETER);

        /** $codepoolEntity \SprykerFeature\Zed\Salesrule\Persistence\SpySalesruleCodePool **/
        $codePoolEntity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()->findOneByIdSalesruleCodepool($request->query->get(Codepool::ID_CODE_POOL_URL_PARAMETER));

        return $codePoolEntity->getIsReusable();
    }
}

<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller\Widget;

use SprykerFeature\Shared\Library\TransferLoader;
use Generated\Shared\Transfer\SalesruleConditionTransfer;
use SprykerEngine\Shared\Transfer\AbstractTransferCollection;
use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Condition\DataSource;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Condition as GridCondition;

class ConditionGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return mixed|GridCondition
     */
    protected function initializeGrid(Request $request)
    {
        $conditionGridDataSource = new DataSource();
        return new GridCondition($conditionGridDataSource, null, '/salesrule/condition-grid');
    }

    /**
     * @param $grid
     * @param AbstractTransferCollection $collection
     * @return mixed
     */
    public function handleDestroy($grid, AbstractTransferCollection $collection = null)
    {
        $gridRequest = $grid->getRequest();
        $parameters = $gridRequest->getParameters();

        $this->facadeSalesrule->deleteSalesRuleCondition($parameters['id_salesrule_condition']);
    }

    /**
     * @return AbstractTransferCollection
     */
    protected function loadTransferCollection()
    {
        return new \Generated\Shared\Transfer\SalesRuleConditionTransfer();
    }

    /**
     * @return Condition
     */
    protected function loadTransfer()
    {
        return new \Generated\Shared\Transfer\SalesRuleConditionTransfer();
    }


}

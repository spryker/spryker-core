<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller\Widget;

use SprykerFeature\Shared\Library\TransferLoader;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;
use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Code\DataSource;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Code;

class CodeGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return mixed|Code
     */
    protected function initializeGrid(Request $request)
    {
        $codeGridDataSource = new DataSource();
        return new Code($codeGridDataSource, null, '/salesrule/code-grid');
    }

    /**
     * @param $grid
     * @param \SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection
     * $collection
     * @return mixed
     */
    public function handleDestroy($grid, \SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection $collection = null)
    {
        $gridRequest = $grid->getRequest();
        $parameters = $gridRequest->getParameters();

        if ($this->facadeSalesrule->canDeleteSalesruleCode($parameters['id_salesrule_code'])) {
            $this->facadeSalesrule->deleteCode($parameters['id_salesrule_code']);
        }
    }

    /**
     * @return \SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection
     */
    protected function loadTransferCollection()
    {
        return new \Generated\Shared\Transfer\SalesRuleCodeTransfer();
    }

    /**
     * @return \SprykerFeature\Shared\Salesrule\Transfer\Code
     */
    protected function loadTransfer()
    {
        return new \Generated\Shared\Transfer\SalesRuleCodeTransfer();
    }


}

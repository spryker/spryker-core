<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller\Widget;

use Generated\Shared\Transfer\SalesruleCodeTransfer;
use SprykerFeature\Shared\Library\TransferLoader;
use SprykerEngine\Shared\Transfer\AbstractTransferCollection;
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
     * @param AbstractTransferCollection $collection
     */
    public function handleDestroy($grid, AbstractTransferCollection $collection = null)
    {
        $gridRequest = $grid->getRequest();
        $parameters = $gridRequest->getParameters();

        if ($this->facadeSalesrule->canDeleteSalesruleCode($parameters['id_salesrule_code'])) {
            $this->facadeSalesrule->deleteCode($parameters['id_salesrule_code']);
        }
    }

    /**
     * @return SalesRuleCodeTransfer
     */
    protected function loadTransferCollection()
    {
        return new SalesruleCodeTransfer();
    }

    /**
     * @return SalesruleCodeTransfer
     */
    protected function loadTransfer()
    {
        return new SalesRuleCodeTransfer();
    }


}

<?php

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class SalesFacade extends AbstractFacade
{

    /**
     * @param Order $transferOrder
     * @param RequestInterface $request
     * @return ModelResult
     */
    public function saveOrder(Order $transferOrder, RequestInterface $request)
    {
        return $this->factory
            ->createModelOrderManager(Locator::getInstance(), $this->factory)
            ->saveOrder($transferOrder, $request);
    }
}

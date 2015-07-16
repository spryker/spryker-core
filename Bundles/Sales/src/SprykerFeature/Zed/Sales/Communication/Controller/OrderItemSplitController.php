<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

/**
 * @method SalesFacade getFacade()
 */
class OrderItemSplitController extends AbstractController
{
    /**
     * @param Request $request
     */
    public function splitAction(Request $request)
    {
        $splitResponseTransfer = $this->getFacade()->splitSalesOrderItem(12, 1);

    }
}

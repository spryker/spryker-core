<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Shared\Shipment\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShipmentOrderDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function haveOrderWithoutShipment(QuoteTransfer $quoteTransfer, string $testStateMachineProcessName = null): SaveOrderTransfer
    {
        $testStateMachineProcessName = 'Test01';
        $this->getOmsHelper()->configureTestStateMachine([$testStateMachineProcessName]);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productTransfer = $this->getProductDataHelper()->haveProduct($itemTransfer->toArray());
        }
        $savedOrderTransfer = $this->getSalesDataHelper()->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, $testStateMachineProcessName);

        return $savedOrderTransfer;
    }

    /**
     * @return \Codeception\Module
     */
    protected function getOmsHelper(): Module
    {
        return $this->getModule('\SprykerTest\Zed\Oms\Helper\OmsHelper');
    }

    /**
     * @return \Codeception\Module
     */
    protected function getSalesDataHelper(): Module
    {
        return $this->getModule('\SprykerTest\Shared\Sales\Helper\SalesDataHelper');
    }

    /**
     * @return \Codeception\Module
     */
    protected function getProductDataHelper(): Module
    {
        return $this->getModule('\SprykerTest\Shared\Product\Helper\ProductDataHelper');
    }
}

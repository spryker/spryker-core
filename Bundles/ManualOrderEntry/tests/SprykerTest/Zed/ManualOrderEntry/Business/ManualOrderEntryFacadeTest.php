<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ManualOrderEntry\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ManualOrderEntry
 * @group Business
 * @group Facade
 * @group ManualOrderEntryFacadeTest
 * Add your own group annotations below this line
 */
class ManualOrderEntryFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ManualOrderEntry\ManualOrderEntryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetOrderSourceByIdShouldReturnTransferObjectForExistingOrderSource()
    {
        $idOrderSource = 1;
        $orderSourceTransfer = $this->getFacade()->getOrderSourceById($idOrderSource);

        $this->assertNotNull($orderSourceTransfer);
        $this->assertSame($idOrderSource, $orderSourceTransfer->getIdOrderSource());
    }

    /**
     * @return void
     */
    public function testFindAllOrderSourcesShouldReturnArrayOfTransferObjectsOrderSource()
    {
        $orderSourceTransfers = $this->getFacade()->getAllOrderSources();

        $this->assertNotNull($orderSourceTransfers);
        $orderSourceTransfer = array_pop($orderSourceTransfers);
        $this->assertTrue($orderSourceTransfer instanceof OrderSourceTransfer);
    }

    /**
     * @return void
     */
    public function testHydrateOrderSourceShouldReturnTransferObjectOrderSource()
    {
        $salesOrderEntityTransfer = $this->tester->createEmptySpySalesOrderEntityTransfer();
        $quoteTransfer = $this->tester->createQuoteTransferWithOrderSource();

        $hydratedSalesOrderEntityTransfer = $this->getFacade()->hydrateOrderSource($salesOrderEntityTransfer, $quoteTransfer);

        $this->assertNotNull($hydratedSalesOrderEntityTransfer);
        $this->assertTrue($hydratedSalesOrderEntityTransfer instanceof SpySalesOrderEntityTransfer);
        $this->assertSame($quoteTransfer->getOrderSource()->getIdOrderSource(), $hydratedSalesOrderEntityTransfer->getFkOrderSource());
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntry\Business\ManualOrderEntryFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}

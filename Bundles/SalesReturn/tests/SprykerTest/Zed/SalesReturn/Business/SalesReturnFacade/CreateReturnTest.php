<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group CreateReturnTest
 * Add your own group annotations below this line
 */
class CreateReturnTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturn(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createFakeQuote(
            $this->tester->haveCustomer(),
            $this->tester->haveStore([StoreTransfer::NAME => 'DE'])
        );

        $orderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setReason('Damaged')
            ->setIdSalesReturnItem($orderTransfer->getOrderItems()->getIterator()->current());

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setStore($quoteTransfer->getStore()->getName())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
    }
}

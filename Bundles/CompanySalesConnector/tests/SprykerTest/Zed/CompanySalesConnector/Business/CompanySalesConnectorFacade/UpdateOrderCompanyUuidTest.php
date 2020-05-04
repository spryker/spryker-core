<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacade;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanySalesConnector
 * @group Business
 * @group CompanySalesConnectorFacade
 * @group UpdateOrderCompanyUuidTest
 * Add your own group annotations below this line
 */
class UpdateOrderCompanyUuidTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const COMPANY_UUID = 'uuid-sample';

    /**
     * @var \SprykerTest\Zed\CompanySalesConnector\CompanySalesConnectorBusinessTester
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
    public function testUpdateOrderCompanyUuidUpdatesUuid(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteWithCompanyUser(static::COMPANY_UUID);
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $this->tester->getFacade()->updateOrderCompanyUuid($saveOrderTransfer, $quoteTransfer);

        $orderTransfer = $this->tester->getLocator()
            ->sales()
            ->facade()
            ->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Assert
        $this->assertSame(static::COMPANY_UUID, $orderTransfer->getCompanyUuid());
    }

    /**
     * @return void
     */
    public function testUpdateOrderCompanyUuidThrowsExceptionForMissingIdSalesOrder(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteWithCompanyUser(static::COMPANY_UUID);
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer->setIdSalesOrder(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateOrderCompanyUuid($saveOrderTransfer, $quoteTransfer);
    }
}

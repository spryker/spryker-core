<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacade;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitSalesConnector
 * @group Business
 * @group CompanyBusinessUnitSalesConnectorFacade
 * @group UpdateOrderCompanyBusinessUnitUuidTest
 * Add your own group annotations below this line
 */
class UpdateOrderCompanyBusinessUnitUuidTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const COMPANY_BUSINESS_UNIT_UUID = 'uuid-sample';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorBusinessTester
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
    public function testUpdateOrderCompanyBusinessUnitUuidUpdatesUuid(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteWithCompanyUser(static::COMPANY_BUSINESS_UNIT_UUID);
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $this->tester->getFacade()->updateOrderCompanyBusinessUnitUuid($saveOrderTransfer, $quoteTransfer);

        $orderTransfer = $this->tester->getLocator()
            ->sales()
            ->facade()
            ->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Assert
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_UUID, $orderTransfer->getCompanyBusinessUnitUuid());
    }

    /**
     * @return void
     */
    public function testUpdateOrderCompanyBusinessUnitUuidThrowsExceptionForMissingIdSalesOrder(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteWithCompanyUser(static::COMPANY_BUSINESS_UNIT_UUID);
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer->setIdSalesOrder(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateOrderCompanyBusinessUnitUuid($saveOrderTransfer, $quoteTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostDeletePluginInterface;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreDeletePluginInterface;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group DeleteSalesOrderAmendmentTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderAmendmentTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentDeleter::ERROR_MESSAGE_SALES_ORDER_AMENDMENT_NOT_FOUND
     *
     * @var string
     */
    protected const ERROR_MESSAGE_SALES_ORDER_AMENDMENT_NOT_FOUND = 'sales_order_amendment.error.not_found';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([SalesOrderAmendmentBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldDeleteSalesOrderAmendmentFromPersistenceByUuid(): void
    {
        // Arrange
        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();
        $salesOrderAmendmentDeleteCriteriaTransfer = (new SalesOrderAmendmentDeleteCriteriaTransfer())
            ->setUuid($salesOrderAmendmentTransfer->getUuidOrFail());

        // Act
        $salesOrderAmendmentResponseTransfer = $this->tester->getFacade()
            ->deleteSalesOrderAmendment($salesOrderAmendmentDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentResponseTransfer->getErrors());
        $this->assertNotNull($salesOrderAmendmentResponseTransfer->getSalesOrderAmendment());

        $this->assertNull(
            $this->tester->findSalesOrderAmendmentByOriginalOrderReference($salesOrderAmendmentTransfer->getOriginalOrderReferenceOrFail()),
        );
    }

    /**
     * @return void
     */
    public function testShouldDeleteSalesOrderAmendmentFromPersistenceByIdSalesOrderAmendment(): void
    {
        // Arrange
        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();
        $salesOrderAmendmentDeleteCriteriaTransfer = (new SalesOrderAmendmentDeleteCriteriaTransfer())
            ->setIdSalesOrderAmendment($salesOrderAmendmentTransfer->getIdSalesOrderAmendmentOrFail());

        // Act
        $salesOrderAmendmentResponseTransfer = $this->tester->getFacade()
            ->deleteSalesOrderAmendment($salesOrderAmendmentDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentResponseTransfer->getErrors());
        $this->assertNotNull($salesOrderAmendmentResponseTransfer->getSalesOrderAmendment());

        $this->assertNull(
            $this->tester->findSalesOrderAmendmentByOriginalOrderReference($salesOrderAmendmentTransfer->getOriginalOrderReferenceOrFail()),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorMessageWhenSalesOrderAmendmentNotFound(): void
    {
        $salesOrderAmendmentDeleteCriteriaTransfer = (new SalesOrderAmendmentDeleteCriteriaTransfer())
            ->setUuid('non-existing-uuid');

        // Act
        $salesOrderAmendmentResponseTransfer = $this->tester->getFacade()
            ->deleteSalesOrderAmendment($salesOrderAmendmentDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentResponseTransfer->getErrors());
        $this->assertSame(
            static::ERROR_MESSAGE_SALES_ORDER_AMENDMENT_NOT_FOUND,
            $salesOrderAmendmentResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentPreDeletePluginsStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_PRE_DELETE,
            [$this->createSalesOrderAmendmentPreDeletePluginMock()],
        );

        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();
        $salesOrderAmendmentDeleteCriteriaTransfer = (new SalesOrderAmendmentDeleteCriteriaTransfer())
            ->setIdSalesOrderAmendment($salesOrderAmendmentTransfer->getIdSalesOrderAmendmentOrFail());

        // Act
        $this->tester->getFacade()->deleteSalesOrderAmendment($salesOrderAmendmentDeleteCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentPostDeletePluginsStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_POST_DELETE,
            [$this->createSalesOrderAmendmentPostDeletePluginMock()],
        );

        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();
        $salesOrderAmendmentDeleteCriteriaTransfer = (new SalesOrderAmendmentDeleteCriteriaTransfer())
            ->setIdSalesOrderAmendment($salesOrderAmendmentTransfer->getIdSalesOrderAmendmentOrFail());

        // Act
        $this->tester->getFacade()->deleteSalesOrderAmendment($salesOrderAmendmentDeleteCriteriaTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreDeletePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderAmendmentPreDeletePluginMock(): SalesOrderAmendmentPreDeletePluginInterface
    {
        $salesOrderAmendmentPreUpdatePluginMock = $this->getMockBuilder(SalesOrderAmendmentPreDeletePluginInterface::class)
            ->getMock();

        $salesOrderAmendmentPreUpdatePluginMock
            ->expects($this->once())
            ->method('preDelete')
            ->willReturnCallback(function (SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer) {
                return $salesOrderAmendmentTransfer;
            });

        return $salesOrderAmendmentPreUpdatePluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostDeletePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderAmendmentPostDeletePluginMock(): SalesOrderAmendmentPostDeletePluginInterface
    {
        $salesOrderAmendmentPostUpdatePluginMock = $this->getMockBuilder(SalesOrderAmendmentPostDeletePluginInterface::class)
            ->getMock();

        $salesOrderAmendmentPostUpdatePluginMock
            ->expects($this->once())
            ->method('postDelete')
            ->willReturnCallback(function (SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer) {
                return $salesOrderAmendmentTransfer;
            });

        return $salesOrderAmendmentPostUpdatePluginMock;
    }
}

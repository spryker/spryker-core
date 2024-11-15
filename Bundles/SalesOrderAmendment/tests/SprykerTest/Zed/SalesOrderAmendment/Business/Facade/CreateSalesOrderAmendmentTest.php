<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostCreatePluginInterface;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreCreatePluginInterface;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group CreateSalesOrderAmendmentTest
 * Add your own group annotations below this line
 */
class CreateSalesOrderAmendmentTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\UniqueOrderSalesOrderAmendmentValidatorRule::GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DUPLICATED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DUPLICATED = 'sales_order_amendment.validation.order_amendment_duplicated';

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
    public function testShouldPersistSalesOrderAmendment(): void
    {
        // Arrange
        $salesOrderAmendmentRequestTransfer = $this->tester->createSalesOrderAmendmentRequestTransfer();

        // Act
        $salesOrderAmendmentResponseTransfer = $this->tester->getFacade()
            ->createSalesOrderAmendment($salesOrderAmendmentRequestTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentResponseTransfer->getErrors());
        $this->assertNotNull($salesOrderAmendmentResponseTransfer->getSalesOrderAmendment());
        $this->assertNotNull($this->tester->findSalesOrderAmendmentByOrderReference(
            $salesOrderAmendmentRequestTransfer->getAmendmentOrderReferenceOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testShouldReturnValidationErrorWhenSalesOrderAmendmentForOrderAlreadyExists(): void
    {
        // Arrange
        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();
        $salesOrderAmendmentRequestTransfer = (new SalesOrderAmendmentRequestTransfer())
            ->setAmendmentOrderReference($salesOrderAmendmentTransfer->getAmendmentOrderReferenceOrFail())
            ->setAmendedOrderReference($salesOrderAmendmentTransfer->getAmendedOrderReferenceOrFail());

        // Act
        $salesOrderAmendmentResponseTransfer = $this->tester->getFacade()
            ->createSalesOrderAmendment($salesOrderAmendmentRequestTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DUPLICATED,
            $salesOrderAmendmentResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentPreCreatePluginsStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_PRE_CREATE,
            [$this->createSalesOrderAmendmentPreCreatePluginMock()],
        );

        $salesOrderAmendmentRequestTransfer = $this->tester->createSalesOrderAmendmentRequestTransfer();

        // Act
        $this->tester->getFacade()->createSalesOrderAmendment($salesOrderAmendmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentPostCreatePluginsStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_POST_CREATE,
            [$this->createSalesOrderAmendmentPostCreatePluginMock()],
        );

        $salesOrderAmendmentRequestTransfer = $this->tester->createSalesOrderAmendmentRequestTransfer();

        // Act
        $this->tester->getFacade()->createSalesOrderAmendment($salesOrderAmendmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentValidatorRulePluginsStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_CREATE_VALIDATION_RULE,
            [$this->tester->createSalesOrderAmendmentValidatorRulePluginMock()],
        );

        $salesOrderAmendmentRequestTransfer = $this->tester->createSalesOrderAmendmentRequestTransfer();

        // Act
        $this->tester->getFacade()->createSalesOrderAmendment($salesOrderAmendmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenAmendmentOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentRequestTransfer = (new SalesOrderAmendmentRequestTransfer())
            ->setAmendmentOrderReference(null)
            ->setAmendedOrderReference('order-reference');

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "amendmentOrderReference" for transfer %s.', SalesOrderAmendmentRequestTransfer::class));

        // Act
        $this->tester->getFacade()
            ->createSalesOrderAmendment($salesOrderAmendmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenAmendedOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentRequestTransfer = (new SalesOrderAmendmentRequestTransfer())
            ->setAmendmentOrderReference('order-reference')
            ->setAmendedOrderReference(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "amendedOrderReference" for transfer %s.', SalesOrderAmendmentRequestTransfer::class));

        // Act
        $this->tester->getFacade()
            ->createSalesOrderAmendment($salesOrderAmendmentRequestTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreCreatePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderAmendmentPreCreatePluginMock(): SalesOrderAmendmentPreCreatePluginInterface
    {
        $salesOrderAmendmentPreCreatePluginMock = $this->getMockBuilder(SalesOrderAmendmentPreCreatePluginInterface::class)
            ->getMock();

        $salesOrderAmendmentPreCreatePluginMock
            ->expects($this->once())
            ->method('preCreate')
            ->willReturnCallback(function (SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer) {
                return $salesOrderAmendmentTransfer;
            });

        return $salesOrderAmendmentPreCreatePluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostCreatePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderAmendmentPostCreatePluginMock(): SalesOrderAmendmentPostCreatePluginInterface
    {
        $salesOrderAmendmentPostCreatePluginMock = $this->getMockBuilder(SalesOrderAmendmentPostCreatePluginInterface::class)
            ->getMock();

        $salesOrderAmendmentPostCreatePluginMock
            ->expects($this->once())
            ->method('postCreate')
            ->willReturnCallback(function (SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer) {
                return $salesOrderAmendmentTransfer;
            });

        return $salesOrderAmendmentPostCreatePluginMock;
    }
}

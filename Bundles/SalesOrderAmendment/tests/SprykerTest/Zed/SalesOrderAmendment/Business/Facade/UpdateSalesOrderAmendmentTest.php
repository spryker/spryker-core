<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\SalesOrderAmendmentBuilder;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostUpdatePluginInterface;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreUpdatePluginInterface;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group UpdateSalesOrderAmendmentTest
 * Add your own group annotations below this line
 */
class UpdateSalesOrderAmendmentTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentExistsSalesOrderAmendmentValidatorRule::GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DOES_NOT_EXIST = 'sales_order_amendment.validation.sales_order_amendment_does_not_exist';

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
    public function testShouldUpdateExistingSalesOrderAmendment(): void
    {
        // Arrange
        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();

        // Act
        $salesOrderAmendmentResponseTransfer = $this->tester->getFacade()
            ->updateSalesOrderAmendment($salesOrderAmendmentTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentResponseTransfer->getErrors());
        $this->assertNotNull($salesOrderAmendmentResponseTransfer->getSalesOrderAmendment());
    }

    /**
     * @return void
     */
    public function testShouldReturnValidationErrorWhenSalesOrderAmendmentDoesNotExist(): void
    {
        // Arrange
        $salesOrderAmendmentTransfer = (new SalesOrderAmendmentBuilder([
            SalesOrderAmendmentTransfer::UUID => 'non-existing-uuid',
        ]))->build();

        // Act
        $salesOrderAmendmentResponseTransfer = $this->tester->getFacade()
            ->updateSalesOrderAmendment($salesOrderAmendmentTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DOES_NOT_EXIST,
            $salesOrderAmendmentResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentPreUpdatePluginsStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_PRE_UPDATE,
            [$this->createSalesOrderAmendmentPreUpdatePluginMock()],
        );

        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();

        // Act
        $this->tester->getFacade()->updateSalesOrderAmendment($salesOrderAmendmentTransfer);
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentPostUpdatePluginsStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_POST_UPDATE,
            [$this->createSalesOrderAmendmentPostUpdatePluginMock()],
        );

        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();

        // Act
        $this->tester->getFacade()->updateSalesOrderAmendment($salesOrderAmendmentTransfer);
    }

    /**
     * @return void
     */
    public function testShouldExecuteSalesOrderAmendmentValidatorRulePluginsStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_UPDATE_VALIDATION_RULE,
            [$this->tester->createSalesOrderAmendmentValidatorRulePluginMock()],
        );

        $salesOrderAmendmentTransfer = $this->tester->createSalesOrderAmendment();

        // Act
        $this->tester->getFacade()->updateSalesOrderAmendment($salesOrderAmendmentTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenUuidIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentTransfer = (new SalesOrderAmendmentBuilder([
            SalesOrderAmendmentTransfer::UUID => null,
        ]))->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "uuid" for transfer %s.', SalesOrderAmendmentTransfer::class));

        // Act
        $this->tester->getFacade()
            ->updateSalesOrderAmendment($salesOrderAmendmentTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreUpdatePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderAmendmentPreUpdatePluginMock(): SalesOrderAmendmentPreUpdatePluginInterface
    {
        $salesOrderAmendmentPreUpdatePluginMock = $this->getMockBuilder(SalesOrderAmendmentPreUpdatePluginInterface::class)
            ->getMock();

        $salesOrderAmendmentPreUpdatePluginMock
            ->expects($this->once())
            ->method('preUpdate')
            ->willReturnCallback(function (SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer) {
                return $salesOrderAmendmentTransfer;
            });

        return $salesOrderAmendmentPreUpdatePluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostUpdatePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderAmendmentPostUpdatePluginMock(): SalesOrderAmendmentPostUpdatePluginInterface
    {
        $salesOrderAmendmentPostUpdatePluginMock = $this->getMockBuilder(SalesOrderAmendmentPostUpdatePluginInterface::class)
            ->getMock();

        $salesOrderAmendmentPostUpdatePluginMock
            ->expects($this->once())
            ->method('postUpdate')
            ->willReturnCallback(function (SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer) {
                return $salesOrderAmendmentTransfer;
            });

        return $salesOrderAmendmentPostUpdatePluginMock;
    }
}

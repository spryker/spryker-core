<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Sales\OrderAmendmentDefaultOrderItemInitialStateProviderPlugin;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group OrderAmendmentDefaultOrderItemInitialStateProviderPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentDefaultOrderItemInitialStateProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STATE_PAYMENT_PENDING = 'payment pending';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester
     */
    protected SalesOrderAmendmentOmsCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnInitialOrderItemState(): void
    {
        // Arrange
        $configMock = $this->tester->mockConfigMethod('getOrderAmendmentOrderItemInitialState', static::STATE_PAYMENT_PENDING);
        $factoryMock = $this->tester->getFactory();
        $factoryMock->setConfig($configMock);

        $plugin = new OrderAmendmentDefaultOrderItemInitialStateProviderPlugin();
        $plugin->setBusinessFactory($factoryMock);

        // Act
        $omsOrderItemStateTransfer = $plugin->getInitialItemState(new QuoteTransfer(), new SaveOrderTransfer());

        // Assert
        $this->assertSame(static::STATE_PAYMENT_PENDING, $omsOrderItemStateTransfer->getName());
    }
}

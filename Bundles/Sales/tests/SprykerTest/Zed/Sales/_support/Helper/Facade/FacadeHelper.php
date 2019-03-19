<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Helper\Facade;


use Codeception\Module;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use PHPUnit_Framework_MockObject_MockObject;
use Generated\Shared\DataBuilder\SequenceNumberSettingsBuilder;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\SalesConfig;

class FacadeHelper extends Module
{
    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\SalesConfig $mockedSalesConfig
     *
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacadeWithMockedConfig(PHPUnit_Framework_MockObject_MockObject $mockedSalesConfig): SalesFacadeInterface {
        $salesFacade = $this->createSalesFacade();
        $salesBusinessFactory = $this->createBusinessFactory();

        $mockedSalesConfig->method('determineProcessForOrderItem')->willReturn('DummyPayment01');
        $mockedSalesConfig->method('getOrderReferenceDefaults')->willReturn(
            $this->createSequenceNumberSettingsTransfer()
        );

        $salesBusinessFactory->setConfig($mockedSalesConfig);
        $salesFacade->setFactory($salesBusinessFactory);

        return $salesFacade;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade(): SalesFacadeInterface
    {
        return new SalesFacade();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesBusinessFactory
     */
    protected function createBusinessFactory(): SalesBusinessFactory
    {
        return new SalesBusinessFactory();
    }

    /**
     * Defines the prefix for the sequence number which is the public id of an order.
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    protected function createSequenceNumberSettingsTransfer(): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsBuilder([
            'name' => SalesConstants::NAME_ORDER_REFERENCE,
            'prefix' => 'DE--',
        ]))->build();
    }
}
<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\SalesProductConnector\Communication\Plugin\Checkout\ItemMetadataSaverPlugin;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method void pause()
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesProductConnectorBusinessTester extends Actor
{
    use _generated\SalesProductConnectorBusinessTesterActions;

    /**
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderByStateMachineProcessName(string $stateMachineProcessName): OrderTransfer
    {
        $quoteTransfer = $this->buildFakeQuote(
            $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE'])
        );

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName, [new ItemMetadataSaverPlugin()]);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteTransfer
    {
        $productConcreteTransfer = $this->haveProduct();

        $productImageSetTransfer = $this->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer)
            ->addItem(
                (new ItemBuilder())->build()
                    ->setSku($productConcreteTransfer->getSku())
                    ->setImages($productImageSetTransfer->getProductImages())
            );

        return $quoteTransfer;
    }
}

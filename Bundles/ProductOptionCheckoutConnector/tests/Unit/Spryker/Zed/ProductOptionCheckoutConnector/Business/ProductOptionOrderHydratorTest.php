<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionCheckoutConnector\Business\ProductOptionOrderHydrator;

/**
 * @group Spryker
 * @group Zed
 * @group ProducOptionCheckoutConnector
 * @group Business
 * @group ProductOptionOrderHydrator
 */
class ProductOptionOrderHydratorTest extends \PHPUnit_Framework_TestCase
{

    const CONCRETE_SKU = 'concrete sku';

    const LABEL_OPTION_TYPE = 'label option type';

    /**
     * @return void
     */
    public function testHydratorTransfersProductOptionsFromCartItemToOrderItem()
    {
        $hydrator = $this->getHydrator();

        $order = $this->getOrderFixture();

        $hydrator->hydrateOrderTransfer($order, $this->getRequestFixture());

        $hydratedOrderItem = $order->getItems()[0];
        $productOptions = $hydratedOrderItem->getProductOptions();

        $this->assertCount(1, $productOptions);
        $this->assertEquals(self::LABEL_OPTION_TYPE, $productOptions[0]->getLabelOptionType());
    }

    /**
     * @return \Spryker\Zed\ProductOptionCheckoutConnector\Business\ProductOptionOrderHydrator
     */
    public function getHydrator()
    {
        return new ProductOptionOrderHydrator();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    private function getOrderFixture()
    {
        $orderItem = new ItemTransfer();
        $orderItem->setSku(self::CONCRETE_SKU);

        $order = new OrderTransfer();
        $order->addItem($orderItem);

        return $order;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutRequestTransfer
     */
    private function getRequestFixture()
    {
        $productOption = new ProductOptionTransfer();
        $productOption->setLabelOptionType(self::LABEL_OPTION_TYPE);

        $cartItem = new ItemTransfer();
        $cartItem->setSku(self::CONCRETE_SKU);
        $cartItem->addProductOption($productOption);

        $cart = new CartTransfer();
        $cart->addItem($cartItem);

        $request = new CheckoutRequestTransfer();
        $request->setCart($cart);

        return $request;
    }

}

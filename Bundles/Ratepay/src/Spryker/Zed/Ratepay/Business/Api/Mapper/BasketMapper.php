<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayRequestShoppingBasketTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;

class BasketMapper extends BaseMapper
{

    const DEFAULT_DISCOUNT_NODE_VALUE = 'Discount';
    const DEFAULT_DISCOUNT_TAX_RATE = 0;
    const DEFAULT_DISCOUNT_UNIT_PRICE = 0;

    const DEFAULT_SHIPPING_NODE_VALUE = 'Shipping costs';
    const DEFAULT_SHIPPING_TAX_RATE = 0;

    const BASKET_DISCOUNT_COEFFICIENT = -1;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentRequestTransfer
     */
    protected $ratepayPaymentRequestTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer,
        RatepayRequestTransfer $requestTransfer
    ) {
        $this->ratepayPaymentRequestTransfer = $ratepayPaymentRequestTransfer;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $shippingUnitPrice = $this->centsToDecimal($this->ratepayPaymentRequestTransfer->requireExpenseTotal()->getExpenseTotal());
        $grandTotal = $this->centsToDecimal($this->ratepayPaymentRequestTransfer->requireGrandTotal()->getGrandTotal());
        $discountTotal = $this->centsToDecimal($this->ratepayPaymentRequestTransfer->requireDiscountTotal()->getDiscountTotal());

        $this->requestTransfer
            ->setShoppingBasket(new RatepayRequestShoppingBasketTransfer())->getShoppingBasket()
            ->setAmount($grandTotal)
            ->setCurrency($this->ratepayPaymentRequestTransfer->requireCurrencyIso3()->getCurrencyIso3())

            ->setShippingUnitPrice($shippingUnitPrice)
            ->setShippingTitle(self::DEFAULT_SHIPPING_NODE_VALUE)
            ->setShippingTaxRate($this->ratepayPaymentRequestTransfer->getShippingTaxRate())

            ->setDiscountTitle(self::DEFAULT_DISCOUNT_NODE_VALUE)
            ->setDiscountUnitPrice($discountTotal * self::BASKET_DISCOUNT_COEFFICIENT)
            ->setDiscountTaxRate(self::DEFAULT_DISCOUNT_TAX_RATE);
    }

}

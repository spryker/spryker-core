<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayRequestShoppingBasketTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

class BasketMapper extends BaseMapper
{
    public const DEFAULT_DISCOUNT_NODE_VALUE = 'Discount';
    public const DEFAULT_DISCOUNT_TAX_RATE = 0;
    public const DEFAULT_DISCOUNT_UNIT_PRICE = 0;

    public const DEFAULT_SHIPPING_NODE_VALUE = 'Shipping costs';
    public const DEFAULT_SHIPPING_TAX_RATE = 0;

    public const BASKET_DISCOUNT_COEFFICIENT = -1;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentRequestTransfer
     */
    protected $ratepayPaymentRequestTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     */
    public function __construct(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer,
        RatepayRequestTransfer $requestTransfer,
        RatepayToMoneyInterface $moneyFacade
    ) {
        $this->ratepayPaymentRequestTransfer = $ratepayPaymentRequestTransfer;
        $this->requestTransfer = $requestTransfer;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @return void
     */
    public function map()
    {
        $shippingUnitPrice = $this->moneyFacade->convertIntegerToDecimal((int)$this->ratepayPaymentRequestTransfer->requireExpenseTotal()->getExpenseTotal());
        $grandTotal = $this->moneyFacade->convertIntegerToDecimal((int)$this->ratepayPaymentRequestTransfer->requireGrandTotal()->getGrandTotal());
        $discountTotal = $this->moneyFacade->convertIntegerToDecimal((int)$this->ratepayPaymentRequestTransfer->getDiscountTotal());

        $this->requestTransfer
            ->setShoppingBasket(new RatepayRequestShoppingBasketTransfer())->getShoppingBasket()
            ->setAmount($grandTotal)
            ->setCurrency($this->ratepayPaymentRequestTransfer->requireCurrencyIso3()->getCurrencyIso3())

            ->setShippingUnitPrice($shippingUnitPrice)
            ->setShippingTitle(self::DEFAULT_SHIPPING_NODE_VALUE)
            ->setShippingTaxRate($this->ratepayPaymentRequestTransfer->getShippingTaxRate())

            ->setDiscountTitle(self::DEFAULT_DISCOUNT_NODE_VALUE)
            ->setDiscountUnitPrice($discountTotal * self::BASKET_DISCOUNT_COEFFICIENT)
            ->setDiscountTaxRate($this->ratepayPaymentRequestTransfer->getDiscountTaxRate());
    }
}

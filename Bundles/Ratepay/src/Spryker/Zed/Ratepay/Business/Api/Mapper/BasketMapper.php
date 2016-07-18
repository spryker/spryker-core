<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

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
     * @var \Generated\Shared\Transfer\QuoteTransfer|\Generated\Shared\Transfer\OrderTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        $quoteTransfer,
        $ratepayPaymentTransfer,
        RatepayRequestTransfer $requestTransfer
    ) {
        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();
        $shippingUnitPrice = $this->centsToDecimal($totalsTransfer->requireExpenseTotal()->getExpenseTotal());

        $grandTotal = $this->centsToDecimal($totalsTransfer->requireGrandTotal()->getGrandTotal());
        $this->requestTransfer->setShoppingBasket(new RatepayRequestShoppingBasketTransfer())->getShoppingBasket()
            ->setAmount($grandTotal)
            ->setCurrency($this->ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3())

            ->setShippingUnitPrice($shippingUnitPrice)
            ->setShippingTitle(self::DEFAULT_SHIPPING_NODE_VALUE)
            ->setShippingTaxRate(self::DEFAULT_SHIPPING_TAX_RATE)

            ->setDiscountTitle(self::DEFAULT_DISCOUNT_NODE_VALUE)
            ->setDiscountUnitPrice(self::DEFAULT_DISCOUNT_UNIT_PRICE * self::BASKET_DISCOUNT_COEFFICIENT)
            ->setDiscountTaxRate(self::DEFAULT_DISCOUNT_TAX_RATE);
    }

}

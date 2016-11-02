<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\RatepayRequestShoppingBasketTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;

class PartialBasketMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|\Generated\Shared\Transfer\OrderTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected $basketItems;

    /**
     * @var bool
     */
    protected $needToSendShipping;

    /**
     * @var int
     */
    protected $discountTotal;

    /**
     * @var float
     */
    protected $discountTaxRate;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $basketItems
     * @param bool $needToSendShipping
     * @param int $discountTotal
     * @param float $discountTaxRate
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        $quoteTransfer,
        $ratepayPaymentTransfer,
        array $basketItems,
        $needToSendShipping,
        $discountTotal,
        $discountTaxRate,
        RatepayRequestTransfer $requestTransfer
    ) {

        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->basketItems = $basketItems;
        $this->needToSendShipping = $needToSendShipping;
        $this->discountTotal = $discountTotal;
        $this->discountTaxRate = $discountTaxRate;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $grandTotal = 0;
        foreach ($this->basketItems as $basketItem) {
            $grandTotal += $basketItem->getUnitGrossPriceWithProductOptions() * $basketItem->getQuantity();
        }
        if (!$this->requestTransfer->getShoppingBasket()) {
            $this->requestTransfer->setShoppingBasket(new RatepayRequestShoppingBasketTransfer());
        }
        $shoppingBasket = $this->requestTransfer->getShoppingBasket();
        $shoppingBasket
            ->setCurrency($this->ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3())

            ->setDiscountTitle(BasketMapper::DEFAULT_DISCOUNT_NODE_VALUE)
            ->setDiscountUnitPrice($this->centsToDecimal($this->discountTotal) * BasketMapper::BASKET_DISCOUNT_COEFFICIENT)
            ->setDiscountTaxRate($this->discountTaxRate);
        $grandTotal -= $this->discountTotal;

        if ($this->needToSendShipping) {
            $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();
            $shippingUnitPrice = $totalsTransfer->requireExpenseTotal()->getExpenseTotal();
            $grandTotal += $shippingUnitPrice;

            $shoppingBasket
                ->setShippingUnitPrice($this->centsToDecimal($shippingUnitPrice))
                ->setShippingTitle(BasketMapper::DEFAULT_SHIPPING_NODE_VALUE)
                ->setShippingTaxRate(BasketMapper::DEFAULT_SHIPPING_TAX_RATE);
        }
        $shoppingBasket->setAmount($this->centsToDecimal($grandTotal));

        if (count($this->quoteTransfer->getExpenses())) {
            $this->requestTransfer->getShoppingBasket()
                ->setShippingTaxRate($this->quoteTransfer->getExpenses()[0]->getTaxRate());
        }
    }

}

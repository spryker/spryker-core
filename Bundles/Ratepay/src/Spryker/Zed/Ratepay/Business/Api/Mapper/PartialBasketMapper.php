<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\RatepayRequestShoppingBasketTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

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
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $basketItems
     * @param bool $needToSendShipping
     * @param int $discountTotal
     * @param float $discountTaxRate
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     */
    public function __construct(
        $quoteTransfer,
        $ratepayPaymentTransfer,
        array $basketItems,
        $needToSendShipping,
        $discountTotal,
        $discountTaxRate,
        RatepayRequestTransfer $requestTransfer,
        RatepayToMoneyInterface $moneyFacade
    ) {
        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->basketItems = $basketItems;
        $this->needToSendShipping = $needToSendShipping;
        $this->discountTotal = $discountTotal;
        $this->discountTaxRate = $discountTaxRate;
        $this->requestTransfer = $requestTransfer;
        $this->moneyFacade = $moneyFacade;
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
            ->setDiscountUnitPrice($this->moneyFacade->convertIntegerToDecimal((int)$this->discountTotal) * BasketMapper::BASKET_DISCOUNT_COEFFICIENT)
            ->setDiscountTaxRate($this->discountTaxRate);

        $grandTotal -= $this->discountTotal;

        if ($this->needToSendShipping) {
            $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();
            $shippingUnitPrice = $totalsTransfer->requireExpenseTotal()->getExpenseTotal();
            $grandTotal += $shippingUnitPrice;

            $shoppingBasket
                ->setShippingUnitPrice($this->moneyFacade->convertIntegerToDecimal((int)$shippingUnitPrice))
                ->setShippingTitle(BasketMapper::DEFAULT_SHIPPING_NODE_VALUE)
                ->setShippingTaxRate(BasketMapper::DEFAULT_SHIPPING_TAX_RATE);
        }
        $shoppingBasket->setAmount($this->moneyFacade->convertIntegerToDecimal((int)$grandTotal));

        if (count($this->quoteTransfer->getExpenses())) {
            $this->requestTransfer->getShoppingBasket()
                ->setShippingTaxRate($this->quoteTransfer->getExpenses()[0]->getTaxRate());
        }
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RatepayRequestShoppingBasketTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

class PartialBasketMapper extends BaseMapper
{
    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $partialOrderTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var bool
     */
    protected $needToSendShipping;

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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $partialOrderTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param bool $needToSendShipping
     * @param float $discountTaxRate
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     */
    public function __construct(
        OrderTransfer $orderTransfer,
        OrderTransfer $partialOrderTransfer,
        TransferInterface $ratepayPaymentTransfer,
        $needToSendShipping,
        $discountTaxRate,
        RatepayRequestTransfer $requestTransfer,
        RatepayToMoneyInterface $moneyFacade
    ) {
        $this->orderTransfer = $orderTransfer;
        $this->partialOrderTransfer = $partialOrderTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->needToSendShipping = $needToSendShipping;
        $this->discountTaxRate = $discountTaxRate;
        $this->requestTransfer = $requestTransfer;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @return void
     */
    public function map()
    {
        $partialOrderTotals = $this->partialOrderTransfer->getTotals();

        $this->initBasketIfEmpty();
        $shoppingBasket = $this->requestTransfer->getShoppingBasket();
        $shoppingBasket
            ->setCurrency($this->ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3())
            ->setDiscountTitle(BasketMapper::DEFAULT_DISCOUNT_NODE_VALUE)
            ->setDiscountUnitPrice($this->moneyFacade->convertIntegerToDecimal((int)$partialOrderTotals->getDiscountTotal()) * BasketMapper::BASKET_DISCOUNT_COEFFICIENT)
            ->setDiscountTaxRate($this->discountTaxRate);

        $grandTotal = $partialOrderTotals->getGrandTotal();

        if ($this->needToSendShipping) {
            $totalsTransfer = $this->orderTransfer->requireTotals()->getTotals();
            $shippingUnitPrice = $totalsTransfer->requireExpenseTotal()->getExpenseTotal();
            $grandTotal += $shippingUnitPrice;

            $shoppingBasket
                ->setShippingUnitPrice($this->moneyFacade->convertIntegerToDecimal((int)$shippingUnitPrice))
                ->setShippingTitle(BasketMapper::DEFAULT_SHIPPING_NODE_VALUE)
                ->setShippingTaxRate(BasketMapper::DEFAULT_SHIPPING_TAX_RATE);
        }
        $shoppingBasket->setAmount($this->moneyFacade->convertIntegerToDecimal((int)$grandTotal));

        if (count($this->orderTransfer->getExpenses())) {
            $this->requestTransfer->getShoppingBasket()
                ->setShippingTaxRate($this->orderTransfer->getExpenses()[0]->getTaxRate());
        }
    }

    /**
     * @return void
     */
    protected function initBasketIfEmpty()
    {
        if (!$this->requestTransfer->getShoppingBasket()) {
            $this->requestTransfer->setShoppingBasket(new RatepayRequestShoppingBasketTransfer());
        }
    }
}

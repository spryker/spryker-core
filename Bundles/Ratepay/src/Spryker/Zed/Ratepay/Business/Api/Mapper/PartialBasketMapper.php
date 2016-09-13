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
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $basketItems
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        $quoteTransfer,
        $ratepayPaymentTransfer,
        array $basketItems,
        RatepayRequestTransfer $requestTransfer
    ) {

        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->basketItems = $basketItems;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $grandTotal = 0;
        foreach ($this->basketItems as $basketItem) {
            $grandTotal += $basketItem->getSumGrossPriceWithProductOptionAndDiscountAmounts();
        }

        $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();
        $shippingUnitPrice = $this->centsToDecimal($totalsTransfer->requireExpenseTotal()->getExpenseTotal());

        $this->requestTransfer->setShoppingBasket(new RatepayRequestShoppingBasketTransfer())->getShoppingBasket()
            ->setAmount($this->centsToDecimal($grandTotal))
            ->setCurrency($this->ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3())

            ->setShippingUnitPrice($shippingUnitPrice)
            ->setShippingTitle(BasketMapper::DEFAULT_SHIPPING_NODE_VALUE)
            ->setShippingTaxRate(BasketMapper::DEFAULT_SHIPPING_TAX_RATE)

            ->setDiscountTitle(BasketMapper::DEFAULT_DISCOUNT_NODE_VALUE)
            ->setDiscountUnitPrice(BasketMapper::DEFAULT_DISCOUNT_UNIT_PRICE * BasketMapper::BASKET_DISCOUNT_COEFFICIENT)
            ->setDiscountTaxRate(BasketMapper::DEFAULT_DISCOUNT_TAX_RATE);

        if (count($this->quoteTransfer->getExpenses())) {
            $this->requestTransfer->getShoppingBasket()
                ->setShippingTaxRate($this->quoteTransfer->getExpenses()[0]->getTaxRate());
        }
    }

}

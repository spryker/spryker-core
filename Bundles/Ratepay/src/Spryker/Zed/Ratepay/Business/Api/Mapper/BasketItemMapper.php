<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RatepayRequestShoppingBasketItemTransfer;
use Generated\Shared\Transfer\RatepayRequestShoppingBasketTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

class BasketItemMapper extends BaseMapper
{
    /**
     * @var \Generated\Shared\Transfer\ItemTransfer
     */
    protected $itemTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     */
    public function __construct(
        ItemTransfer $itemTransfer,
        RatepayRequestTransfer $requestTransfer,
        RatepayToMoneyInterface $moneyFacade
    ) {
        $this->itemTransfer = $itemTransfer;
        $this->requestTransfer = $requestTransfer;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @return void
     */
    public function map()
    {
        $itemPrice = $this->itemTransfer
            ->requireUnitGrossPriceWithProductOptions()
            ->getUnitGrossPriceWithProductOptions();
        $itemPrice = $this->moneyFacade->convertIntegerToDecimal((int)$itemPrice);

        $itemTransfer = (new RatepayRequestShoppingBasketItemTransfer())
            ->setItemName($this->itemTransfer->requireName()->getName())
            ->setArticleNumber($this->itemTransfer->requireSku()->getSku())
            ->setUniqueArticleNumber($this->itemTransfer->requireGroupKey()->getGroupKey())
            ->setQuantity($this->itemTransfer->requireQuantity()->getQuantity())
            ->setTaxRate($this->itemTransfer->requireTaxRate()->getTaxRate())
            ->setDescription($this->itemTransfer->getDescription())
            ->setDescriptionAddition($this->itemTransfer->getDescriptionAddition())
            ->setUnitPriceGross($itemPrice);

        $productOptions = [];
        foreach ($this->itemTransfer->getProductOptions() as $productOption) {
            $productOptions[] = $productOption->getLabelOptionValue();
        }

        $itemTransfer->setProductOptions($productOptions);

        $this->initBasketIfEmpty();
        $this->requestTransfer->getShoppingBasket()->addItems($itemTransfer);
    }

    /**
     * @return float
     */
    protected function getBasketItemDiscount()
    {
        $itemDiscount = $this->itemTransfer->getUnitTotalDiscountAmountWithProductOption();
        $itemDiscount = $this->moneyFacade->convertIntegerToDecimal((int)$itemDiscount);

        return $itemDiscount;
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

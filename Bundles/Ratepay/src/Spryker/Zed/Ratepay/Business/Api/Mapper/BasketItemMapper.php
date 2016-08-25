<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RatepayRequestShoppingBasketItemTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;

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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        ItemTransfer $itemTransfer,
        RatepayRequestTransfer $requestTransfer
    ) {
        $this->itemTransfer = $itemTransfer;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $itemPrice = $this->itemTransfer
            ->requireUnitGrossPriceWithProductOptions()
            ->getUnitGrossPriceWithProductOptions();
        $itemPrice = $this->centsToDecimal($itemPrice);

        $itemTransfer = (new RatepayRequestShoppingBasketItemTransfer())
            ->setItemName($this->itemTransfer->requireName()->getName())
            ->setArticleNumber($this->itemTransfer->requireSku()->getSku())
            ->setUniqueArticleNumber($this->itemTransfer->requireGroupKey()->getGroupKey())
            ->setQuantity($this->itemTransfer->requireQuantity()->getQuantity())
            ->setTaxRate($this->itemTransfer->requireTaxRate()->getTaxRate())
            ->setDescription($this->itemTransfer->getDescription())
            ->setDescriptionAddition($this->itemTransfer->getDescriptionAddition())
            ->setUnitPriceGross($itemPrice);

        $itemDiscount = $this->getBasketItemDiscount();
        if ($itemDiscount) {
            $itemTransfer->setDiscount($itemDiscount);
        }

        $productOptions = [];
        foreach ($this->itemTransfer->getProductOptions() as $productOption) {
            $productOptions[] = $productOption->getLabelOptionValue();
        }

        $itemTransfer->setProductOptions($productOptions);
        $this->requestTransfer->getShoppingBasket()->addItems($itemTransfer);
    }

    /**
     * @return float
     */
    protected function getBasketItemDiscount()
    {
        //@todo learn how to get discount here?
//        $itemDiscount = $this->itemTransfer
//            ->requireUnitTotalDiscountAmountWithProductOption()
//            ->getUnitTotalDiscountAmountWithProductOption();
//        $itemDiscount = $this->centsToDecimal($itemDiscount);

        $itemDiscount = 0;
        return $itemDiscount;
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Builder;

use Generated\Shared\Transfer\RatepayRequestTransfer;

class ShoppingBasketItem extends AbstractBuilder implements BuilderInterface
{
    public const ROOT_TAG = 'item';

    public const ITEM_DISCOUNT_COEFFICIENT = -1;

    /**
     * @var int
     */
    protected $itemNumber;

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     * @param int $itemNumber
     */
    public function __construct(RatepayRequestTransfer $requestTransfer, $itemNumber)
    {
        parent::__construct($requestTransfer);
        $this->itemNumber = $itemNumber;
    }

    /**
     * @return array
     */
    public function buildData()
    {
        $basketItem = $this->requestTransfer->getShoppingBasket()->getItems()[$this->itemNumber];

        $return = [
            '@article-number' => $basketItem->getArticleNumber(),
            '@unique-article-number' => $basketItem->getUniqueArticleNumber(),
            '@quantity' => $basketItem->getQuantity(),
            '@unit-price-gross' => $basketItem->getUnitPriceGross(),
            '@tax-rate' => $basketItem->getTaxRate(),
            '#' => $basketItem->getItemName(),
        ];
        if ($basketItem->getDiscount() > 0) {
            $return['@discount'] = $basketItem->getDiscount() * self::ITEM_DISCOUNT_COEFFICIENT;
        }
        if ($basketItem->getDescription() !== null) {
            $return['@description'] = $basketItem->getDescription();
        }
        if ($basketItem->getDescriptionAddition() !== null) {
            $return['@description-addition'] = $basketItem->getDescriptionAddition();
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }
}

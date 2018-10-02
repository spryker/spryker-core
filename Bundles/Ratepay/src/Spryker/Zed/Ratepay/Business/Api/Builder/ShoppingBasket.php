<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Builder;

class ShoppingBasket extends AbstractBuilder implements BuilderInterface
{
    public const ROOT_TAG = 'shopping-basket';

    /**
     * @return array
     */
    public function buildData()
    {
        $return = [
            '@amount' => $this->requestTransfer->getShoppingBasket()->getAmount(),
            '@currency' => $this->requestTransfer->getShoppingBasket()->getCurrency(),
            'items' => [],
        ];

        if ($this->requestTransfer->getShoppingBasket()->getShippingUnitPrice()) {
            $return['shipping'] = [
                '@unit-price-gross' => $this->requestTransfer->getShoppingBasket()->getShippingUnitPrice(),
                '@tax-rate' => $this->requestTransfer->getShoppingBasket()->getShippingTaxRate(),
                '#' => $this->requestTransfer->getShoppingBasket()->getShippingTitle(),
            ];
        }

        if ($this->requestTransfer->getShoppingBasket()->getDiscountUnitPrice()) {
            $return['discount'] = [
                '@unit-price-gross' => $this->requestTransfer->getShoppingBasket()->getDiscountUnitPrice(),
                '@tax-rate' => $this->requestTransfer->getShoppingBasket()->getDiscountTaxRate(),
                '#' => $this->requestTransfer->getShoppingBasket()->getDiscountTitle(),
            ];
        }

        $items = $this->requestTransfer->getShoppingBasket()->getItems()->getArrayCopy();
        foreach (array_keys($items) as $itemNumber) {
            $return['items'][] = (new ShoppingBasketItem($this->requestTransfer, $itemNumber));
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

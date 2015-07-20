<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Communication\Plugin\CartGroupProvider;

use Generated\Shared\Cart\CartItemInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Cart\Business\Model\ItemGrouping\GroupingProviderInterface;

class ProductSkuPlugin extends AbstractPlugin implements GroupingProviderInterface
{
    /**
     * @param CartItemInterface $cartItem
     *
     * @return string
     */
    public function buildPart(CartItemInterface $cartItem)
    {
        return $cartItem->getSku();
    }
}

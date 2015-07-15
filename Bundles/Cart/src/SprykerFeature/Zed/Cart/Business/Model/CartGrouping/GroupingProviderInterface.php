<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Model\CartGrouping;

use Generated\Shared\Cart\CartItemInterface;

interface GroupingProviderInterface
{
    /**
     * @param CartItemInterface $cartItem
     *
     * @return string
     */
    public function buildPart(CartItemInterface $cartItem);
}

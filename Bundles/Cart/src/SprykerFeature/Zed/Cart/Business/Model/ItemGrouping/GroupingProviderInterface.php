<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Model\ItemGrouping;

use Generated\Shared\Cart\GroupKeyParameterInterface;

interface GroupingProviderInterface
{
    /**
     * @param GroupKeyParameterInterface $cartItem
     *
     * @return string
     */
    public function buildPart(GroupKeyParameterInterface $cartItem);
}

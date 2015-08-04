<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Bundles\Wishlist\src\SprykerFeature\Zed\Wishlist\Communication\Controller\Plugins;

use Bundles\Wishlist\src\SprykerFeature\Zed\Wishlist\Dependency\PreSavePluginInterface;
use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;

class PreSaveSkuGroupKeyPlugin implements PreSavePluginInterface
{
    /**
     * @param WishlistChangeInterface $wishlist
     */
    public function trigger(WishlistChangeInterface $wishlist)
    {
        foreach ($wishlist->getItems() as $item) {
            $item->setGroupKey($this->buildKey($item));
        }
    }

    /**
     * @param ItemInterface $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemInterface $cartItem)
    {
        $groupKey = $cartItem->getGroupKey();
        if (empty($groupKey)) {
            return $cartItem->getAbstractSku();
        }

        $groupKey = $groupKey . '-' . $cartItem->getAbstractSku();

        return $groupKey;

    }
}

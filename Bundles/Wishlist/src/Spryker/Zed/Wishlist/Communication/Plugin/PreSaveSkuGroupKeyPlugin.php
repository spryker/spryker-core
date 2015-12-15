<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Wishlist\Dependency\PreSavePluginInterface;

class PreSaveSkuGroupKeyPlugin extends AbstractPlugin implements PreSavePluginInterface
{

    /**
     * @param ItemTransfer[] $items
     *
     * @return void
     */
    public function trigger(\ArrayObject $items)
    {
        foreach ($items as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }
    }

    /**
     * @param ItemTransfer $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $cartItem)
    {
        $groupKey = $cartItem->getGroupKey();
        if (empty($groupKey)) {
            return $cartItem->getSku();
        }

        $groupKey = $groupKey . '-' . $cartItem->getSku();

        return $groupKey;
    }

}

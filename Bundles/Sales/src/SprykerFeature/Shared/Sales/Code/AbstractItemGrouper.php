<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Sales\Code;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @TODO Validate cross-bundle Dependencies
 */
abstract class AbstractItemGrouper
{

    const GROUP_KEY_SKU = 'Sku';
    const GROUP_KEY_UNIQUE_IDENTIFIER = 'UniqueIdentifier';

    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param OrderItemCollection $items
     *
     * @return OrderItemCollection
     */
    public function groupItemsByUniqueId(OrderItemCollection $items)
    {
        return $this->groupItemsByKey($items, self::GROUP_KEY_UNIQUE_IDENTIFIER);
    }

    /**
     * This Method is not Options aware. Use with caution
     *
     * @param OrderItemCollection $items
     *
     * @return OrderItemCollection
     */
    public function groupItemsBySku(OrderItemCollection $items)
    {
        return $this->groupItemsByKey($items, self::GROUP_KEY_SKU);
    }

    /**
     * @param OrderItemCollection $items
     * @param string $key
     *
     * @return OrderItemCollection
     * @static
     */
    protected function groupItemsByKey(OrderItemCollection $items, $key)
    {
        $index = [];
        $methodName = 'get' . ucfirst($key);

        /** @var \SprykerFeature\Shared\Sales\Transfer\OrderItem $item */
        foreach ($items as $item) {
            $groupKey = $item->$methodName();
            if (isset($index[$groupKey])) {
                $index[$groupKey]->setQuantity($index[$groupKey]->getQuantity() + 1);
                $index[$groupKey]->setGrossPrice($index[$groupKey]->getGrossPrice() + $item->getGrossPrice());
                $index[$groupKey]->setPriceToPay($index[$groupKey]->getPriceToPay() + $item->getPriceToPay());

            } else {
                $newItem = clone $item;
                $newItem->setUnitGrossPrice($newItem->getGrossPrice());
                $newItem->setUnitPriceToPay($newItem->getPriceToPay());
                $index[$groupKey] = $newItem;
            }
        }
        $transferItems = new \Generated\Shared\Transfer\ItemTransfer();
        $transferItems->fromArray($index);

        return $transferItems;
    }

}

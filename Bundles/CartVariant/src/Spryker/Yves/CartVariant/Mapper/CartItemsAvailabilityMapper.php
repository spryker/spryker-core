<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartVariant\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Spryker\Yves\CartVariant\Dependency\Client\CartVariantToAvailabilityStorageClientBridgeInterface;

class CartItemsAvailabilityMapper implements CartItemsMapperInterface
{
    public const CONCRETE_PRODUCT_AVAILABLE_ITEMS = 'concrete_product_available_items';

    /**
     * @var \Spryker\Yves\CartVariant\Dependency\Client\CartVariantToAvailabilityStorageClientBridgeInterface
     */
    protected $availabilityStorageClient;

    /**
     * @param \Spryker\Yves\CartVariant\Dependency\Client\CartVariantToAvailabilityStorageClientBridgeInterface $availabilityStorageClient
     */
    public function __construct(CartVariantToAvailabilityStorageClientBridgeInterface $availabilityStorageClient)
    {
        $this->availabilityStorageClient = $availabilityStorageClient;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    public function buildMap(ArrayObject $items)
    {
        $availabilityMap = [];
        foreach ($items as $item) {
            $availabilityMap = array_replace($availabilityMap, $this->getAvailability($item));
        }

        return $availabilityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return array
     */
    protected function getAvailability(ItemTransfer $item)
    {
        $availability = $this->availabilityStorageClient
            ->getProductAvailabilityByIdProductAbstract($item->getIdProductAbstract())
            ->toArray();

        $availabilityBySku = [];
        foreach ($availability[static::CONCRETE_PRODUCT_AVAILABLE_ITEMS] as $sku => $itemAvailable) {
            $availabilityBySku[$sku][StorageAvailabilityTransfer::CONCRETE_PRODUCT_AVAILABLE_ITEMS] = $itemAvailable;
        }

        return $availabilityBySku;
    }
}

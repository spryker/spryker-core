<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cart\Mapper;

use Generated\Shared\Transfer\StorageAvailabilityTransfer;

class CartItemsAvailabilityMapper implements CartItemsMapperInterface
{

    const CONCRETE_PRODUCTS_AVAILABILITY = 'concrete_products_availability';
    const CONCRETE_PRODUCT_AVAILABLE_ITEMS = 'concrete_product_available_items';

    /**
     * @var \Spryker\Client\Availability\AvailabilityClientInterface
     */
    protected $productAvailabilityClient;

    /**
     * @param \Spryker\Client\Availability\AvailabilityClientInterface $productAvailabilityClient
     */
    public function __construct($productAvailabilityClient)
    {
        $this->productAvailabilityClient = $productAvailabilityClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    public function buildMap($items)
    {
        $availabilityMap = [];
        foreach ($items as $item) {
            $availabilityMap = array_merge($availabilityMap, $this->getAvailability($item));
        }
        return $availabilityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return array
     */
    protected function getAvailability($item)
    {
        $mapped = [];

        $availability = $this->productAvailabilityClient->getProductAvailabilityByIdProductAbstract($item->getIdProductAbstract())->toArray();

        foreach ($availability[self::CONCRETE_PRODUCT_AVAILABLE_ITEMS] as $sku => $itemAvailable) {
            $mapped[$sku][StorageAvailabilityTransfer::CONCRETE_PRODUCT_AVAILABLE_ITEMS] = $itemAvailable;
        }

        foreach ($availability[self::CONCRETE_PRODUCTS_AVAILABILITY] as $sku => $itemsAvailable) {
            $mapped[$sku][StorageAvailabilityTransfer::CONCRETE_PRODUCTS_AVAILABILITY] = $itemsAvailable;
        }

        return $mapped;
    }

}

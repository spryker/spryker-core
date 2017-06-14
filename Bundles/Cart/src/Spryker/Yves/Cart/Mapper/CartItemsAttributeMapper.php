<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cart\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StorageAttributeMapTransfer;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Spryker\Shared\Cart\CartConstants;

class CartItemsAttributeMapper implements CartItemsMapperInterface
{

    const CONCRETE_PRODUCTS_AVAILABILITY = 'concrete_products_availability';
    const CONCRETE_PRODUCT_AVAILABLE_ITEMS = 'concrete_product_available_items';

    /**
     * @var \Spryker\Client\Product\ProductClientInterface
     */
    protected $productClient;

    /**
     * @var \Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper
     */
    protected $cartItemsAvailabilityMapper;

    /**
     * @param \Spryker\Client\Product\ProductClientInterface $productClient
     * @param \Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper $cartItemsAvailabilityMapper
     */
    public function __construct($productClient, $cartItemsAvailabilityMapper)
    {
        $this->productClient = $productClient;
        $this->cartItemsAvailabilityMapper = $cartItemsAvailabilityMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    public function buildMap(array $items)
    {
        $itemsAvailabilityMap = $this->cartItemsAvailabilityMapper->buildMap($items);
        $availableItemsSkus = array_keys($itemsAvailabilityMap);

        $attributes = [];

        foreach ($items as $item) {

            $attributeMap = $this->getAttributesMapByProductAbstract($item);
            $attributes[$item->getSku()] = $this->getAttributesWithAvailability(
                $item,
                $attributeMap,
                $availableItemsSkus
            );
        }

        return $attributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     * @param array $attributeMap
     * @param array $availableItemsSkus
     *
     * @return array
     */
    protected function getAttributesWithAvailability(ItemTransfer $item, array $attributeMap, array $availableItemsSkus)
    {
        $productConcreteIds = $attributeMap[StorageAttributeMapTransfer::PRODUCT_CONCRETE_IDS];
        $productConcreteSkus = array_flip($productConcreteIds);

        $productVariants= [];

        $shit = $attributeMap[StorageAttributeMapTransfer::ATTRIBUTE_VARIANTS];

        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($shit), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $attribute => $productConcreteId) {
            if ($iterator->callHasChildren() === false) {

                $variantNameValue = $iterator->getSubIterator($iterator->getDepth() -1)->key();
                list($variantName, $variantValue) = explode(':', $variantNameValue);

                if (array_key_exists($variantName, $productVariants) === false || array_key_exists($variantValue, $productVariants[$variantName]) === false) {
                    $productVariants[$variantName][$variantValue][CartConstants::AVAILABLE] = false;
                    $productVariants[$variantName][$variantValue][CartConstants::SELECTED] = false;
                }

                if (in_array($productConcreteSkus[$productConcreteId], $availableItemsSkus)) {
                    $productVariants[$variantName][$variantValue][CartConstants::AVAILABLE] = true;
                }
                if ($productConcreteId === $item->getId()) {
                    $productVariants[$variantName][$variantValue][CartConstants::SELECTED] = true;
                }
            }
        }

        return $productVariants;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return array
     */
    protected function getAttributesMapByProductAbstract(ItemTransfer $item)
    {
        return $this->productClient
            ->getAttributeMapByIdProductAbstractForCurrentLocale($item->getIdProductAbstract());
    }

}

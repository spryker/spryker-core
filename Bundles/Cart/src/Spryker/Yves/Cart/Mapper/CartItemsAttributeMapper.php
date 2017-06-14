<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cart\Mapper;

use Generated\Shared\Transfer\StorageAttributeMapTransfer;

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
    public function buildMap($items)
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
    protected function getAttributesWithAvailability($item, array $attributeMap, array $availableItemsSkus)
    {
        $productConcreteIds = $attributeMap['productConcreteIds'];
        $productConcreteSkus = array_flip($productConcreteIds);

        $productVariants = [];

        foreach ($attributeMap[StorageAttributeMapTransfer::ATTRIBUTE_VARIANTS] as $variantNameValue => $variant) {
            foreach ($variant as $options) {
                foreach ((array)$options as $productConcreteId) {
                    list($variantName, $variantValue) = explode(':', $variantNameValue);
                    if (array_key_exists($variantName, $productVariants) === false || array_key_exists($variantValue, $productVariants[$variantName]) === false) {
                        $productVariants[$variantName][$variantValue]['available'] = false;
                        $productVariants[$variantName][$variantValue]['selected'] = false;
                    }

                    if (in_array($productConcreteSkus[$productConcreteId], $availableItemsSkus)) {
                        $productVariants[$variantName][$variantValue]['available'] = true;
                    }
                    if ($productConcreteId === $item->getId()) {
                        $productVariants[$variantName][$variantValue]['selected'] = true;
                    }
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
    protected function getAttributesMapByProductAbstract($item)
    {
        return $this->productClient
            ->getAttributeMapByIdProductAbstractForCurrentLocale($item->getIdProductAbstract());
    }

}

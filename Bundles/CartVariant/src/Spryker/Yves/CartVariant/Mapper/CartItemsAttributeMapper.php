<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartVariant\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StorageAttributeMapTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Spryker\Shared\CartVariant\CartVariantConstants;
use Spryker\Yves\CartVariant\Dependency\Client\CartVariantToProductClientBridgeInterface;

class CartItemsAttributeMapper implements CartItemsMapperInterface
{
    public const CONCRETE_PRODUCTS_AVAILABILITY = 'concrete_products_availability';
    public const CONCRETE_PRODUCT_AVAILABLE_ITEMS = 'concrete_product_available_items';

    /**
     * @var \Spryker\Yves\CartVariant\Dependency\Client\CartVariantToProductClientBridgeInterface
     */
    protected $productClient;

    /**
     * @var \Spryker\Yves\CartVariant\Mapper\CartItemsMapperInterface
     */
    protected $cartItemsAvailabilityMapper;

    /**
     * @param \Spryker\Yves\CartVariant\Dependency\Client\CartVariantToProductClientBridgeInterface $productClient
     * @param \Spryker\Yves\CartVariant\Mapper\CartItemsMapperInterface $cartItemsAvailabilityMapper
     */
    public function __construct(CartVariantToProductClientBridgeInterface $productClient, CartItemsMapperInterface $cartItemsAvailabilityMapper)
    {
        $this->productClient = $productClient;
        $this->cartItemsAvailabilityMapper = $cartItemsAvailabilityMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $items
     *
     * @return array
     */
    public function buildMap(ArrayObject $items)
    {
        $itemsAvailabilityMap = $this->cartItemsAvailabilityMapper->buildMap($items);
        $availableItemsSkus = $this->getAvailableItemsSku($itemsAvailabilityMap);

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
        $availableConcreteProductsSku = $this->getAvailableConcreteProductsSku($attributeMap);

        $productVariants = [];

        $attributeMapIterator = $this->createAttributeIterator($attributeMap);

        foreach ($attributeMapIterator as $attribute => $productConcreteId) {
            if ($attributeMapIterator->callHasChildren() === true) {
                continue;
            }

            $variantNameValue = $this->getParentNode($attributeMapIterator);
            [$variantName, $variantValue] = explode(':', $variantNameValue);

            if ($this->isVariantNotSet($variantName, $productVariants, $variantValue)) {
                $productVariants[$variantName][$variantValue][CartVariantConstants::AVAILABLE] = false;
                $productVariants[$variantName][$variantValue][CartVariantConstants::SELECTED] = false;
            }

            if ($this->isItemSkuAvailable($availableItemsSkus, $availableConcreteProductsSku, $productConcreteId)) {
                $productVariants[$variantName][$variantValue][CartVariantConstants::AVAILABLE] = true;
            }
            if ($productConcreteId === $item->getId()) {
                $productVariants[$variantName][$variantValue][CartVariantConstants::SELECTED] = true;
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

    /**
     * @param array $itemsAvailabilityMap
     *
     * @return array
     */
    protected function getAvailableItemsSku(array $itemsAvailabilityMap)
    {
        $availableItemsSku = [];
        foreach ($itemsAvailabilityMap as $sku => $availability) {
            if ($availability[StorageAvailabilityTransfer::CONCRETE_PRODUCT_AVAILABLE_ITEMS]) {
                $availableItemsSku[] = $sku;
            }
        }

        return $availableItemsSku;
    }

    /**
     * @param array $attributeMap
     *
     * @return array
     */
    protected function getAvailableConcreteProductsSku(array $attributeMap)
    {
        $productConcreteSkus = [];
        if (array_key_exists(StorageAttributeMapTransfer::PRODUCT_CONCRETE_IDS, $attributeMap)) {
            $productConcreteIds = $attributeMap[StorageAttributeMapTransfer::PRODUCT_CONCRETE_IDS];
            $productConcreteSkus = array_flip($productConcreteIds);
        }

        return $productConcreteSkus;
    }

    /**
     * @param array $attributeMap
     *
     * @return \RecursiveIteratorIterator
     */
    protected function createAttributeIterator(array $attributeMap)
    {
        $attributeMapIterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($attributeMap[StorageAttributeMapTransfer::ATTRIBUTE_VARIANTS]),
            RecursiveIteratorIterator::SELF_FIRST
        );

        return $attributeMapIterator;
    }

    /**
     * @param string $variantName
     * @param array $productVariants
     * @param string $variantValue
     *
     * @return bool
     */
    protected function isVariantNotSet($variantName, array $productVariants, $variantValue)
    {
        return array_key_exists($variantName, $productVariants) === false || array_key_exists(
            $variantValue,
            $productVariants[$variantName]
        ) === false;
    }

    /**
     * @param array $availableItemsSkus
     * @param array $availableConcreteProductsSku
     * @param int $productConcreteId
     *
     * @return bool
     */
    protected function isItemSkuAvailable(array $availableItemsSkus, array $availableConcreteProductsSku, $productConcreteId)
    {
        return in_array($availableConcreteProductsSku[$productConcreteId], $availableItemsSkus, true);
    }

    /**
     * @param \RecursiveIteratorIterator $attributeMapIterator
     *
     * @return string
     */
    protected function getParentNode(RecursiveIteratorIterator $attributeMapIterator)
    {
        return $attributeMapIterator->getSubIterator($attributeMapIterator->getDepth() - 1)->key();
    }
}

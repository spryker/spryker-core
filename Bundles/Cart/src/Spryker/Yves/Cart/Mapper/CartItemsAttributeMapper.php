<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cart\Mapper;

use Generated\Shared\Transfer\StorageAttributeMapTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;

class CartItemsAttributeMapper implements CartItemsMapperInterface
{

    const ATTRIBUTES = 'attributes';
    const AVAILABILITY = 'availability';
    const CONCRETE_PRODUCTS_AVAILABILITY = 'concrete_products_availability';
    const CONCRETE_PRODUCT_AVAILABLE_ITEMS = 'concrete_product_available_items';

    /**
     * @var \Spryker\Client\Product\ProductClientInterface
     */
    protected $productOptionsClient;

    /**
     * @var \Spryker\Client\Availability\AvailabilityClientInterface
     */
    protected $productAvailabilityClient;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var \Spryker\Client\Product\ProductClientInterface
     */
    protected $productClient;

    /**
     * @param \Spryker\Client\Product\ProductClientInterface $productClient
     */
    public function __construct($productClient)
    {
        $this->productClient = $productClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    public function buildMap($items)
    {
        $attributes = [];
        foreach ($items as $item) {
            $attributes[$item->getSku()] = $this->getAttributesBySku($item);
        }

        return $attributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return array
     */
    protected function getAttributesBySku($item)
    {
        $attributes = $this->getAttributesMapByProductAbstract($item);

        $selectedAttributes = $this->getSelectedAttributes($item);

        return $this->markAsSelected($attributes[StorageAttributeMapTransfer::SUPER_ATTRIBUTES], $selectedAttributes);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return array
     */
    protected function getSelectedAttributes($item)
    {
        $selectedAttributes = [];

        $attributes = $this->getAttributesMapByProductAbstract($item);

        foreach ($attributes[StorageAttributeMapTransfer::ATTRIBUTE_VARIANTS] as $variantName => $variant) {
            foreach ($variant as $options) {
                foreach ((array)$options as $option) {
                    if ($option === $item->getId()) {
                        $this->extractKeyValue($selectedAttributes, $variantName);
                    }
                }
            }
        }

        return $selectedAttributes;
    }

    /**
     * @param array $attributes
     * @param array $selectedAttributes
     *
     * @return array
     */
    protected function markAsSelected($attributes, $selectedAttributes)
    {
        $result = [];

        foreach ($attributes as $name => $attributeList) {
            foreach ($attributeList as $attribute) {
                if ($selectedAttributes[$name] === $attribute) {
                    $result[$name][$attribute] = true;
                    continue;
                }

                $result[$name][$attribute] = false;
            }
        }

        return $result;
    }

    /**
     * @param array $selectedAttributes
     * @param string $strVal
     * @param string $delimiter
     *
     * @return void
     */
    protected function extractKeyValue(array &$selectedAttributes, $strVal, $delimiter = ':')
    {
        list($key, $value) = explode($delimiter, $strVal);
        $selectedAttributes[$key] = $value;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return array
     */
    protected function getAttributesMapByProductAbstract($item)
    {
        if (array_key_exists($item->getSku(), $this->attributes) === false) {
            $this->attributes[$item->getSku()] = $this->productClient->getAttributeMapByIdProductAbstractForCurrentLocale($item->getIdProductAbstract());
        }
        return $this->attributes[$item->getSku()];
    }

}

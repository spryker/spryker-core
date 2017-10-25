<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Map\Collector;

use Generated\Shared\Transfer\ProductSearchAttributeMapTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface;
use Spryker\Zed\ProductSearch\Business\Exception\InvalidFilterTypeException;
use Spryker\Zed\ProductSearch\ProductSearchConfig;

class ProductSearchAttributeCollector implements ProductSearchAttributeMapCollectorInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface
     */
    protected $attributeReader;

    /**
     * @var \Spryker\Zed\ProductSearch\ProductSearchConfig
     */
    protected $productSearchConfig;

    /**
     * @var \Generated\Shared\Transfer\ProductSearchAttributeTransfer[]
     */
    protected static $attributeList;

    /**
     * @param \Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface $attributeReader
     * @param \Spryker\Zed\ProductSearch\ProductSearchConfig $productSearchConfig
     */
    public function __construct(AttributeReaderInterface $attributeReader, ProductSearchConfig $productSearchConfig)
    {
        $this->attributeReader = $attributeReader;
        $this->productSearchConfig = $productSearchConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductSearchAttributeMapTransfer[]
     */
    public function getProductSearchAttributeMap()
    {
        $result = [];
        $availableProductSearchFilterConfigs = $this->productSearchConfig->getAvailableProductSearchFilterConfigs();

        foreach ($this->getAttributeList() as $productSearchAttributeTransfer) {
            $this->assertFilterType($availableProductSearchFilterConfigs, $productSearchAttributeTransfer);

            $targetField = $availableProductSearchFilterConfigs[$productSearchAttributeTransfer->getFilterType()]->getFieldName();

            $result[] = (new ProductSearchAttributeMapTransfer())
                ->setAttributeName($productSearchAttributeTransfer->getKey())
                ->setTargetFields([$targetField]);
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer[]
     */
    protected function getAttributeList()
    {
        if (static::$attributeList === null) {
            static::$attributeList = $this->attributeReader->getAttributeList();
        }

        return static::$attributeList;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer[] $availableProductSearchFilterConfigs
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @throws \Spryker\Zed\ProductSearch\Business\Exception\InvalidFilterTypeException
     *
     * @return void
     */
    protected function assertFilterType(array $availableProductSearchFilterConfigs, ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        if (!isset($availableProductSearchFilterConfigs[$productSearchAttributeTransfer->getFilterType()])) {
            throw new InvalidFilterTypeException(sprintf(
                'Invalid filter type "%s"! Available options are [%s].',
                $productSearchAttributeTransfer->getFilterType(),
                implode(', ', array_keys($availableProductSearchFilterConfigs))
            ));
        }
    }
}

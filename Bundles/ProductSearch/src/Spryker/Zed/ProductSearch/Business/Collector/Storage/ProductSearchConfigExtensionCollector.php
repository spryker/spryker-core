<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Collector\Storage;

use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Spryker\Shared\ProductSearch\ProductSearchConstants;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface;
use Spryker\Zed\ProductSearch\Business\Exception\InvalidFilterTypeException;
use Spryker\Zed\ProductSearch\ProductSearchConfig;

class ProductSearchConfigExtensionCollector extends AbstractStoragePropelCollector
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
     * @param \Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface $attributeReader
     * @param \Spryker\Zed\ProductSearch\ProductSearchConfig $productSearchConfig
     */
    public function __construct(AttributeReaderInterface $attributeReader, ProductSearchConfig $productSearchConfig)
    {
        $this->attributeReader = $attributeReader;
        $this->productSearchConfig = $productSearchConfig;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @throws \Spryker\Zed\ProductSearch\Business\Exception\InvalidFilterTypeException
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $searchConfigExtensionTransfer = new SearchConfigExtensionTransfer();

        // TODO: refactor
        $filterTypeConfigs = $this->productSearchConfig->getFilterTypeConfigs();

        $productSearchAttributeTransfers = $this->attributeReader->getAttributeList();
        foreach ($productSearchAttributeTransfers as $productSearchAttributeTransfer) {
            if (!isset($filterTypeConfigs[$productSearchAttributeTransfer->getFilterType()])) {
                throw new InvalidFilterTypeException(sprintf(
                    'Invalid filter type "%s"! Available options are [%s].',
                    $productSearchAttributeTransfer->getFilterType(),
                    implode(', ', array_keys($filterTypeConfigs))
                ));
            }

            $facetConfigTransfer = clone $filterTypeConfigs[$productSearchAttributeTransfer->getFilterType()];

            $facetConfigTransfer
                ->setName($productSearchAttributeTransfer->getKey())
                ->setParameterName($productSearchAttributeTransfer->getKey());

            $searchConfigExtensionTransfer->addFacetConfig($facetConfigTransfer);
        }

        return $searchConfigExtensionTransfer->toArray();
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductSearchConstants::RESOURCE_TYPE_PRODUCT_SEARCH_CONFIG_EXTENSION;
    }

}

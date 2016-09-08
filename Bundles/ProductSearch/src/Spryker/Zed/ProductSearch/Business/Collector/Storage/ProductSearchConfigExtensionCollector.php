<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Collector\Storage;

use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
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
     * @var \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface $attributeReader
     * @param \Spryker\Zed\ProductSearch\ProductSearchConfig $productSearchConfig
     */
    public function __construct(AttributeReaderInterface $attributeReader, ProductSearchConfig $productSearchConfig, KeyBuilderInterface $keyBuilder)
    {
        $this->attributeReader = $attributeReader;
        $this->productSearchConfig = $productSearchConfig;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $searchConfigExtensionTransfer = new SearchConfigExtensionTransfer();

        $availableProductSearchFilterConfigs = $this->productSearchConfig->getAvailableProductSearchFilterConfigs();

        $productSearchAttributeTransfers = $this->attributeReader->getAttributeList();
        foreach ($productSearchAttributeTransfers as $productSearchAttributeTransfer) {
            $this->assertFilterType($availableProductSearchFilterConfigs, $productSearchAttributeTransfer);

            $facetConfigTransfer = clone $availableProductSearchFilterConfigs[$productSearchAttributeTransfer->getFilterType()];

            $facetConfigTransfer
                ->setName($productSearchAttributeTransfer->getKey())
                ->setParameterName($productSearchAttributeTransfer->getKey());

            $searchConfigExtensionTransfer->addFacetConfig($facetConfigTransfer);
        }

        return $searchConfigExtensionTransfer->toArray();
    }

    /**
     * @param mixed $data
     * @param string $localeName
     * @param array $collectedItemData
     *
     * @return string
     */
    protected function collectKey($data, $localeName, array $collectedItemData)
    {
        return $this->keyBuilder->generateKey($data, $localeName);
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductSearchConstants::RESOURCE_TYPE_PRODUCT_SEARCH_CONFIG_EXTENSION;
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

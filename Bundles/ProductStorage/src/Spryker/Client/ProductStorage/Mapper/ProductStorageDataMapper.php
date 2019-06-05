<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Mapper;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface;
use Spryker\Client\ProductStorage\ProductStorageConfig;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;
use Spryker\Shared\Kernel\Store;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToUnderscore;

class ProductStorageDataMapper implements ProductStorageDataMapperInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected $productStorageExpanderPlugins;

    /**
     * @var \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface
     */
    protected $productAbstractVariantsRestrictionFilter;

    /**
     * @uses ProductStorageDataMapper::filterProductStorageExpanderPlugins()
     *
     * @param \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[] $storageProductExpanderPlugins
     * @param \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter
     */
    public function __construct(
        array $storageProductExpanderPlugins,
        ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter
    ) {
        $this->productStorageExpanderPlugins = array_filter($storageProductExpanderPlugins, [$this, 'filterProductStorageExpanderPlugins']);
        $this->productAbstractVariantsRestrictionFilter = $productAbstractVariantsRestrictionFilter;
    }

    /**
     * @param string $locale
     * @param array $productStorageData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData($locale, array $productStorageData, array $selectedAttributes = [])
    {
        $productStorageData = $this->filterAbstractProductVariantsData($productStorageData);
        $productViewTransfer = $this->createProductViewTransfer($productStorageData);
        $productViewTransfer->setSelectedAttributes($selectedAttributes);

        foreach ($this->productStorageExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expandProductViewTransfer($productViewTransfer, $productStorageData, $locale);
        }

        return $productViewTransfer;
    }

    /**
     * @param array $productStorageData
     *
     * @return array
     */
    protected function filterAbstractProductVariantsData(array $productStorageData): array
    {
        return $this->productAbstractVariantsRestrictionFilter->filterAbstractProductVariantsData($productStorageData);
    }

    /**
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function createProductViewTransfer(array $productStorageData)
    {
        if (ProductStorageConfig::isCollectorCompatibilityMode()) {
            return $this->formatCollectorData($productStorageData);
        }

        $productStorageTransfer = new ProductViewTransfer();
        $productStorageTransfer->fromArray($productStorageData, true);

        return $productStorageTransfer;
    }

    /**
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    private function formatCollectorData(array $productStorageData): ProductViewTransfer
    {
        unset($productStorageData['prices'], $productStorageData['categories'], $productStorageData['imageSets']);
        $productStorageData = $this->changeKeys($productStorageData);

        $clientLocatorClassName = Locator::class;
        /** @var \Spryker\Client\Product\ProductClientInterface $productClient */
        $productClient = $clientLocatorClassName::getInstance()->product()->client();

        $attributeMap = $productClient->getAttributeMapByIdAndLocale($productStorageData['id_product_abstract'], Store::getInstance()->getCurrentLocale());
        $attributeMap = $this->changeKeys($attributeMap);

        $productStorageData['attribute_map'] = $attributeMap;

        $productStorageTransfer = new ProductViewTransfer();
        $productStorageTransfer->fromArray($productStorageData, true);

        return $productStorageTransfer;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function changeKeys(array $data): array
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToUnderscore())
            ->attach(new StringToLower());

        $filteredData = [];

        foreach ($data as $key => $value) {
            $filteredData[$filterChain->filter($key)] = $value;
        }

        return $filteredData;
    }

    /**
     * @param \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface $storageProductExpanderPlugin
     *
     * @return bool
     */
    protected function filterProductStorageExpanderPlugins(ProductViewExpanderPluginInterface $storageProductExpanderPlugin): bool
    {
        return true;
    }
}

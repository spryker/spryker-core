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
use Spryker\Shared\Kernel\Store;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToUnderscore;

abstract class AbstractProductStorageDataMapper implements ProductStorageDataMapperInterface
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
     * @param \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[] $storageProductExpanderPlugins
     * @param \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter
     */
    public function __construct(
        array $storageProductExpanderPlugins,
        ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter
    ) {
        $this->productStorageExpanderPlugins = $storageProductExpanderPlugins;
        $this->productAbstractVariantsRestrictionFilter = $productAbstractVariantsRestrictionFilter;
    }

    /**
     * @param string $locale
     * @param array $productStorageData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    abstract public function mapProductStorageData($locale, array $productStorageData, array $selectedAttributes = []);

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
}

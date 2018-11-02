<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Mapper;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface;

class ProductStorageDataMapper implements ProductStorageDataMapperInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected $productAbstractStorageExpanderPlugins;

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
        $this->productAbstractStorageExpanderPlugins = $storageProductExpanderPlugins;
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
        $productStorageData = $this->productAbstractVariantsRestrictionFilter->filterAbstractProductVariantsData($productStorageData);
        $productViewTransfer = $this->createProductViewTransfer($productStorageData);
        $productViewTransfer->setSelectedAttributes($selectedAttributes);

        foreach ($this->productAbstractStorageExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expandProductViewTransfer($productViewTransfer, $productStorageData, $locale);
        }

        return $productViewTransfer;
    }

    /**
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function createProductViewTransfer(array $productStorageData)
    {
        $productStorageTransfer = new ProductViewTransfer();
        $productStorageTransfer->fromArray($productStorageData, true);

        return $productStorageTransfer;
    }
}

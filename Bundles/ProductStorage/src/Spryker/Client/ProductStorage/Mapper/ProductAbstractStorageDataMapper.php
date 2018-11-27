<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Mapper;

use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductConcreteViewExpanderExcluderPluginInterface;

class ProductAbstractStorageDataMapper extends AbstractProductStorageDataMapper
{
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

        foreach ($this->productStorageExpanderPlugins as $productViewExpanderPlugin) {
            if ($productViewExpanderPlugin instanceof ProductConcreteViewExpanderExcluderPluginInterface) {
                continue;
            }
            $productViewTransfer = $productViewExpanderPlugin->expandProductViewTransfer($productViewTransfer, $productStorageData, $locale);
        }

        return $productViewTransfer;
    }
}

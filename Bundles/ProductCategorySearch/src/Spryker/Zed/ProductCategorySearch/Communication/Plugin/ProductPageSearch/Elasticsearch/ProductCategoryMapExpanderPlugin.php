<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Communication\Plugin\ProductCategorySearch\Elasticsearch;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductCategorySearch\Communication\ProductCategorySearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategorySearch\Business\ProductCategorySearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategorySearch\ProductCategorySearchConfig getConfig()
 */
class ProductCategoryMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands PageMapTransfer with category map data.
     * - Expands PageMapTransfer with full text search data.
     * - Expands PageMapTransfer with sorting data.
     * - Returns expanded PageMapTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ) {
        return $this->getFacade()->expandProductPageMapWithCategoryData(
            $pageMapTransfer,
            $pageMapBuilder,
            $productData,
            $localeTransfer
        );
    }
}

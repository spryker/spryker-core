<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\ProductPageSearch;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductListMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageMapExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\ProductListSearchConfig getConfig()
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 */
class ProductConcreteProductListPageMapExpanderPlugin extends AbstractPlugin implements ProductConcretePageMapExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided PageMapTransfer with product lists ids.
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
    public function expand(PageMapTransfer $pageMapTransfer, PageMapBuilderInterface $pageMapBuilder, array $productData, LocaleTransfer $localeTransfer): PageMapTransfer
    {
        if (!isset($productData[ProductPageSearchTransfer::PRODUCT_LIST_MAP])) {
            return $pageMapTransfer;
        }

        return $this->setProductListsData($pageMapTransfer, $productData);
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    protected function setProductListsData(PageMapTransfer $pageMapTransfer, array $productData): PageMapTransfer
    {
        $pageMapTransfer->setProductLists(
            $this->getFacade()->mapProductDataToProductListMapTransfer($productData, new ProductListMapTransfer())
        );

        return $pageMapTransfer;
    }
}

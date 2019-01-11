<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Communication\Plugin\ProductPageSearch;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductListMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageMapExpanderPluginInterface;

/**
 * @api
 *
 * @method \Spryker\Zed\ProductList\Business\ProductListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 */
class ProductConcreteProductListPageMapExpanderPlugin extends AbstractPlugin implements ProductConcretePageMapExpanderPluginInterface
{
    /**
     * {@inheritdoc}
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
        $productListMapTransfer = new ProductListMapTransfer();
        $productListMapTransfer->fromArray($productData[ProductPageSearchTransfer::PRODUCT_LIST_MAP]);
        $pageMapTransfer->setProductLists($productListMapTransfer);

        return $pageMapTransfer;
    }
}

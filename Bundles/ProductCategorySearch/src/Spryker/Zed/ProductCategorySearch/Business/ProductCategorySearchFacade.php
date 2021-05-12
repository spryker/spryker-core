<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

/**
 * @method \Spryker\Zed\ProductCategorySearch\Business\ProductCategorySearchBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface getRepository()
 */
class ProductCategorySearchFacade extends AbstractFacade implements ProductCategorySearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageWithCategories(ProductPageLoadTransfer $productPageLoadTransfer): ProductPageLoadTransfer
    {
        return $this->getFactory()
            ->createProductPageCategoryExpander()
            ->expandProductPageWithCategories($productPageLoadTransfer);
    }

    /**
     * {@inheritDoc}
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
    public function expandProductPageMapWithCategoryData(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        return $this->getFactory()
            ->createProductPageMapCategoryExpander()
            ->expandProductPageMapWithCategoryData($pageMapTransfer, $pageMapBuilder, $productData, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageDataWithCategoryData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer): void
    {
        $this->getFactory()
            ->createProductPageDataExpander()
            ->expandProductPageDataWithCategoryData($productData, $productAbstractPageSearchTransfer);
    }
}

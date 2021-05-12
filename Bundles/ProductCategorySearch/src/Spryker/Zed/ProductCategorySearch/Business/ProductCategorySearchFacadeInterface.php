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
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

interface ProductCategorySearchFacadeInterface
{
    /**
     * Specification:
     * - Retrieves product category entities by product abstract ids.
     * - Expands `ProductPayloadTransfer.categories` with product category entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageWithCategories(ProductPageLoadTransfer $productPageLoadTransfer): ProductPageLoadTransfer;

    /**
     * Specification:
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
    public function expandProductPageMapWithCategoryData(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer;

    /**
     * Specification:
     * - Expands `ProductPageSearchTransfer` with category related data.
     * - Populates `ProductPageSearchTransfer.allParentCategoryIds`.
     * - Populates `ProductPageSearchTransfer.categoryNames`.
     * - Populates `ProductPageSearchTransfer.sortedCategories`.
     *
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageDataWithCategoryData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer): void;
}

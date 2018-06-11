<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductCategoryRepositoryInterface
{
    /**
     * Specification:
     * - Retrieve all category names for abstract product by its id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getProductAbstractCategoriesByIdProductAbstract(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer): array;

    /**
     * Specification:
     * - Retrieve all category names for concrete product by its id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getProductConcreteCategoriesByIdProductConcrete(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer): array;
}

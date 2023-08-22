<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer;

interface CategoryUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function createCategoryAssignment(ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer, int $idProductAbstract): void;

    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function updateCategories(ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer, int $idProductAbstract): void;
}

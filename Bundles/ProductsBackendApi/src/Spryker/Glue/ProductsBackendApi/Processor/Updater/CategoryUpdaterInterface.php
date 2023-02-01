<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\ApiProductsAttributesTransfer;

interface CategoryUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function createCategoryAssignment(ApiProductsAttributesTransfer $apiProductsAttributesTransfer, int $idProductAbstract): void;

    /**
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function updateCategories(ApiProductsAttributesTransfer $apiProductsAttributesTransfer, int $idProductAbstract): void;
}

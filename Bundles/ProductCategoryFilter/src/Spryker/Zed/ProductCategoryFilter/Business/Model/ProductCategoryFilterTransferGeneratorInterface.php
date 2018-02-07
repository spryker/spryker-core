<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

interface ProductCategoryFilterTransferGeneratorInterface
{
    /**
     * @param int $idProductCategoryFilter
     * @param int $idCategory
     * @param string $jsonData
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferFromJson($idProductCategoryFilter, $idCategory, $jsonData);

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferWithJsonFromTransfer(ProductCategoryFilterTransfer $productCategoryFilterTransfer);
}

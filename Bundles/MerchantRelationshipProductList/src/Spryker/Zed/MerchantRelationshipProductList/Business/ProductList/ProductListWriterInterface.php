<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\ProductList;

use Generated\Shared\Transfer\MerchantRelationshipDeleteResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;

interface ProductListWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipDeleteResponseTransfer
     */
    public function deleteMerchantRelationshipFromProductList(ProductListTransfer $productListTransfer): MerchantRelationshipDeleteResponseTransfer;
}

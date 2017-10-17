<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

interface ProductCategoryFilterTouchInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return mixed
     */
    public function touchProductCategoryFilterActive(ProductCategoryFilterTransfer $productCategoryFilterTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return mixed
     */
    public function touchProductCategoryFilterDeleted(ProductCategoryFilterTransfer $productCategoryFilterTransfer);
}

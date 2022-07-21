<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Validator;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

interface ProductCategoryFilterValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     * @param array<int, int|string> $searchFilterKeys
     *
     * @return bool
     */
    public function validate(ProductCategoryFilterTransfer $productCategoryFilterTransfer, array $searchFilterKeys): bool;
}

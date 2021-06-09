<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Business\Expander\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;

interface ProductOptionGroupExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function expandProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer): ProductOptionGroupTransfer;
}

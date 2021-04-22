<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Validator;

use Generated\Shared\Transfer\ValidationResponseTransfer;

interface ProductConcreteValidatorInterface
{
    /**
     * @param array $concreteProducts
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateConcreteProducts(array $concreteProducts): ValidationResponseTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Validator;

use Generated\Shared\Transfer\TableValidationResponseTransfer;

interface ProductConcreteValidatorInterface
{
    /**
     * @param array<mixed> $concreteProducts
     *
     * @return \Generated\Shared\Transfer\TableValidationResponseTransfer
     */
    public function validateConcreteProducts(array $concreteProducts): TableValidationResponseTransfer;
}

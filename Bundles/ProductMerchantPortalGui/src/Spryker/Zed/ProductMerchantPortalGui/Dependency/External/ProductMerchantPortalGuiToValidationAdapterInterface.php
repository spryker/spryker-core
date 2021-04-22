<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\External;

use Symfony\Component\Validator\Validator\ValidatorInterface;

interface ProductMerchantPortalGuiToValidationAdapterInterface
{
    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    public function createValidator(): ValidatorInterface;
}

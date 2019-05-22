<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getAddressKeyGenerationExcludedFields(): array
    {
        return [
            AddressTransfer::IS_DEFAULT_BILLING,
            AddressTransfer::IS_DEFAULT_SHIPPING,
        ];
    }
}

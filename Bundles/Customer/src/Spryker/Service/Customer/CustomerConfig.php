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
            AddressTransfer::ID_CUSTOMER_ADDRESS,
            AddressTransfer::ID_SALES_ORDER_ADDRESS,
            AddressTransfer::IS_DEFAULT_BILLING,
            AddressTransfer::IS_DEFAULT_SHIPPING,
            AddressTransfer::ID_COMPANY_UNIT_ADDRESS,
            AddressTransfer::FK_CUSTOMER,
            AddressTransfer::FK_COUNTRY,
            AddressTransfer::FK_MISC_COUNTRY,
            AddressTransfer::FK_REGION,
            AddressTransfer::UUID,
            AddressTransfer::KEY,
            AddressTransfer::IS_ADDRESS_SAVING_SKIPPED,
            AddressTransfer::COUNTRY,
            AddressTransfer::REGION,
        ];
    }
}

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
     * @deprecated Use getAddressKeyGenerationWhiteListedFields() instead.
     *
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

    /**
     * @return string[]
     */
    public function getAddressKeyGenerationWhiteListedFields(): array
    {
        return [
            AddressTransfer::FIRST_NAME,
            AddressTransfer::LAST_NAME,
            AddressTransfer::ADDRESS1,
            AddressTransfer::ADDRESS2,
            AddressTransfer::ADDRESS3,
            AddressTransfer::COMPANY,
            AddressTransfer::CITY,
            AddressTransfer::ZIP_CODE,
            AddressTransfer::STATE,
            AddressTransfer::ISO2_CODE,
            AddressTransfer::EMAIL,
            AddressTransfer::SALUTATION,
            AddressTransfer::PHONE,
            AddressTransfer::MIDDLE_NAME,
            AddressTransfer::CELL_PHONE,
            AddressTransfer::IS_DELETED,
        ];
    }
}

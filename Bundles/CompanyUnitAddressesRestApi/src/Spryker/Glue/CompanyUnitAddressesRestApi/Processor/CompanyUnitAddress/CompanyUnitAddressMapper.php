<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer;

class CompanyUnitAddressMapper implements CompanyUnitAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer $restCompanyUnitAddressAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer
     */
    public function mapCompanyUnitAddressAttributesTransferToRestCompanyUnitAddressAttributesTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        RestCompanyUnitAddressAttributesTransfer $restCompanyUnitAddressAttributesTransfer
    ): RestCompanyUnitAddressAttributesTransfer {
        return $restCompanyUnitAddressAttributesTransfer->fromArray($companyUnitAddressTransfer->toArray(), true);
    }
}

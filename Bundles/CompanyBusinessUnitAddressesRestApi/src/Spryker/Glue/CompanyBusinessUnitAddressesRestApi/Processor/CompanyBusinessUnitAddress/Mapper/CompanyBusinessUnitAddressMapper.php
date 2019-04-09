<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Mapper;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer;

class CompanyBusinessUnitAddressMapper implements CompanyBusinessUnitAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer
     */
    public function mapCompanyUnitAddressTransferToRestCompanyBusinessUnitAddressesAttributesTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
    ): RestCompanyBusinessUnitAddressesAttributesTransfer {
        return $restCompanyBusinessUnitAddressesAttributesTransfer->fromArray($companyUnitAddressTransfer->toArray(), true);
    }
}

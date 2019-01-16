<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer;

interface CompanyBusinessUnitAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer
     */
    public function mapCompanyBusinessUnitAddressAttributesTransferToRestCompanyBusinessUnitAddressAttributesTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
    ): RestCompanyBusinessUnitAddressAttributesTransfer;
}

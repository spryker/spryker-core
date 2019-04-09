<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitsAttributesTransfer;

interface CompanyBusinessUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitsAttributesTransfer $restCompanyBusinessUnitsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitsAttributesTransfer
     */
    public function mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitsAttributesTransfer(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        RestCompanyBusinessUnitsAttributesTransfer $restCompanyBusinessUnitsAttributesTransfer
    ): RestCompanyBusinessUnitsAttributesTransfer;
}

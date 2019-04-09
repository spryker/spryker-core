<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\RestCompaniesAttributesTransfer;

class CompanyMapper implements CompanyMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\RestCompaniesAttributesTransfer $restCompaniesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompaniesAttributesTransfer
     */
    public function mapCompanyTransferToRestCompaniesAttributesTransfer(
        CompanyTransfer $companyTransfer,
        RestCompaniesAttributesTransfer $restCompaniesAttributesTransfer
    ): RestCompaniesAttributesTransfer {
        return $restCompaniesAttributesTransfer->fromArray($companyTransfer->toArray(), true);
    }
}

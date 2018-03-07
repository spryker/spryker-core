<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\SpyCompanyEntityTransfer;

class CompanyMapper implements CompanyMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyEntityTransfer $companyEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyEntityTransfer
     */
    public function mapCompanyTransferToEntityTransfer(
        CompanyTransfer $companyTransfer,
        SpyCompanyEntityTransfer $companyEntityTransfer
    ): SpyCompanyEntityTransfer {
        return $companyEntityTransfer->fromArray(
            $companyTransfer->modifiedToArray(false),
            true
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyEntityTransfer $companyEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function mapEntityTransferToCompanyTransfer(
        SpyCompanyEntityTransfer $companyEntityTransfer,
        CompanyTransfer $companyTransfer
    ): CompanyTransfer {
        return $companyTransfer->fromArray(
            $companyEntityTransfer->toArray(),
            true
        );
    }
}

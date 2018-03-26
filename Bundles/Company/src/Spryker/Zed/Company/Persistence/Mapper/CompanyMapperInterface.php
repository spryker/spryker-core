<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\SpyCompany;

interface CompanyMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Orm\Zed\Company\Persistence\SpyCompany $spyCompany
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompany
     */
    public function mapCompanyTransferToEntity(
        CompanyTransfer $companyTransfer,
        SpyCompany $spyCompany
    ): SpyCompany;

    /**
     * @param \Orm\Zed\Company\Persistence\SpyCompany $spyCompany
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function mapEntityToCompanyTransfer(
        SpyCompany $spyCompany,
        CompanyTransfer $companyTransfer
    ): CompanyTransfer;
}

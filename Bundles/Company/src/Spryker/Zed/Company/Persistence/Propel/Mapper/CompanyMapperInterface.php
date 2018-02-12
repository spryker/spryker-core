<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\SpyCompany;

interface CompanyMapperInterface
{
    /**
     * @param \Orm\Zed\Company\Persistence\SpyCompany $companyEntity
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function mapCompanyEntityToTransfer(SpyCompany $companyEntity): CompanyTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompany
     */
    public function mapCompanyTransferToEntity(CompanyTransfer $companyTransfer): SpyCompany;
}

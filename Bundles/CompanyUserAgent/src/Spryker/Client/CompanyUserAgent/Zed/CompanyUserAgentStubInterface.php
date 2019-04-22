<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserAgent\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;

interface CompanyUserAgentStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer;
}

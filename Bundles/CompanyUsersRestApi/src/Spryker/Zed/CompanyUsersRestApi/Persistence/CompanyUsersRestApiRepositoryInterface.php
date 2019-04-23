<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Persistence;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;

interface CompanyUsersRestApiRepositoryInterface
{
    /**
     * @module CompanyUser
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer): CompanyUserCollectionTransfer;
}

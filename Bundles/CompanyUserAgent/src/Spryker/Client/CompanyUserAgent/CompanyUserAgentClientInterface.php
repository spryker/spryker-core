<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserAgent;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;

interface CompanyUserAgentClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves company user collection according provided criteria.
     * - Searches by at least one of first name, last name, and email.
     * - Applies "limit" when provided.
     * - Populates "Customer" and "Company" properties in returned company users.
     * - Applies "CompanyUserHydrationPluginInterface" plugins on returned company users.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer;
}

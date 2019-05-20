<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalf;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface BusinessOnBehalfClientInterface
{
    /**
     * Specification:
     * - Retrieves a collection of active company users related to the provided customer.
     * - Uses customer ID to find company users.
     * - Hydrates company transfer to company user transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer;

    /**
     * Specification:
     *  - Sets is_default to true for provided company user
     *  - Removes is_default flag for all other company/business unit pairs of a customer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function setDefaultCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     *  - Removes all isDefault flags from company users that belong to the provided customer
     *  - Returns customer transfer object with company user set as null
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function unsetDefaultCompanyUser(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * Specification:
     * - Executes stack of plugins CompanyUserChangeAllowedCheckPluginInterface.
     * - Returns false if at least one plugin returns false.
     * - Returns true by default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isCompanyUserChangeAllowed(CustomerTransfer $customerTransfer): bool;
}

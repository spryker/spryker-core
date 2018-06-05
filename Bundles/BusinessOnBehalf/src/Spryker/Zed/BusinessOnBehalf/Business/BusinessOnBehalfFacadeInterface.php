<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Business;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface BusinessOnBehalfFacadeInterface
{
    /**
     * Specification:
     * - Sets IsOnBehalf property as true when the provided customer has multiple company users connected.
     * - Sets IsOnBehalf property as false otherwise.
     * - Uses provided customer ID to find company users.
     * - Ignores Company user/Customer activity flags.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerWithIsOnBehalf(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * Specification:
     * - Retrieves a collection of active company users related to the provided customer.
     * - Uses customer ID to find company users.
     * - Hydrates company transfer to company user transfer.
     * - Hydrates company business unit transfer to company user transfer.
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
     * - Deselects default company user is_default option.
     * - Makes sent company user to be a default one.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function setDefaultCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer;

    /**
     * Specification:
     * - Remove all is_default flags for customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function unsetDefaultCompanyUserByCustomer(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * Specification:
     * - Finds a company user by idCustomer and is_default = true
     * - Doesn't set anything when a default record does not exist
     * - Doesn't set anything when CustomerTransfer::companyUserTransfer is not null
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setDefaultCompanyUserToCustomer(CustomerTransfer $customerTransfer): CustomerTransfer;
}

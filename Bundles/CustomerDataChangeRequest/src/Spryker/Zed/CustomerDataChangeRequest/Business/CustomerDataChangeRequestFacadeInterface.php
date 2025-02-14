<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business;

use Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerDataChangeResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer;

interface CustomerDataChangeRequestFacadeInterface
{
    /**
     * Specification:
     *  - Retrieves the existing customer data based on the provided customer ID.
     *  - Generates a verification token for the email change request.
     *  - Sends a verification email if the new email differs from the current one.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer
     */
    public function sendVerificationEmail(CustomerTransfer $customerTransfer): VerificationTokenCustomerChangeDataResponseTransfer;

    /**
     * Specification:
     * - Handles customer data change requests by delegating processing to the appropriate strategy.
     * - Iterates through available customer data change request strategies and applies applicable ones. If no strategy matches the request, an error response is returned.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeResponseTransfer
     */
    public function changeCustomerData(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerDataChangeResponseTransfer;

    /**
     * Specification:
     * - Retrieves customer data change request collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer
     */
    public function getCustomerDataChangeRequestCollection(
        CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer
    ): CustomerDataChangeRequestCollectionTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business;

use Generated\Shared\Transfer\CustomerAccessTransfer;

interface CustomerAccessFacadeInterface
{
    /**
     * Specification:
     * - Run installer, that fills table with restricted access to configured content types for unauthenticated customers
     *
     * @api
     *
     * @return void
     */
    public function install(): void;

    /**
     * Specification:
     * - Returns the all content types that the customer can see when not logged-in in a CustomerAccessTransfer object
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnrestrictedContentTypes(): CustomerAccessTransfer;

    /**
     * Specification:
     * - Returns all content types from the database table
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAllContentTypes(): CustomerAccessTransfer;

    /**
     * Specification:
     * - Updates unauthenticated customer access entities
     * - Marks all content types as has no access
     * - Marks listed in CustomerAccessTransfer content types as has access
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function updateUnauthenticatedCustomerAccess(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer;

    /**
     * Specification:
     * - Returns list of accesses for content that unauthenticated customer can not have
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getRestrictedContentTypes(): CustomerAccessTransfer;
}

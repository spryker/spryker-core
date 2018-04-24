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
     * - Installs the necessary data for access of unauthenticated customers from config
     *
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * Specification:
     * - Returns the all content types that the customer can see when not logged-in in a CustomerAccessTransfer object
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess();

    /**
     * Specification:
     * - Returns all content types from the database table
     *
     * @api
     *
     * @return array
     */
    public function findAllContentTypes();

    /**
     * Specification:
     * - Updates only these content types supplied to accessible (hasAccess->true)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return void
     */
    public function updateOnlyContentTypesToAccessible(CustomerAccessTransfer $customerAccessTransfer);
}

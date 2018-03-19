<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessGui\Dependency\Facade;

interface CustomerAccessGuiToCustomerAccessFacadeInterface
{
    /**
     * @param string[] $customerAccessTransfer
     *
     * @return void
     */
    public function updateOnlyContentTypesToAccessible($customerAccessTransfer);

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
     * - Returns the all content types that the customer can see when not logged-in in a CustomerAccessTransfer object
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess();
}

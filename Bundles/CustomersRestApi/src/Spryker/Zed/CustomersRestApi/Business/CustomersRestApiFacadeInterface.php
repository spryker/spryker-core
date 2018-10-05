<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CustomersRestApi\Business;

interface CustomersRestApiFacadeInterface
{
    /**
     * Specification:
     * - Retrieves the list of addresses that do not have the uuid set.
     * - Saves them one by one to trigger uuid generation.
     *
     * @api
     *
     * @return void
     */
    public function updateCustomerAddressUuid(): void;
}

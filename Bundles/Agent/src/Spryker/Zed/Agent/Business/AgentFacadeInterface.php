<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AgentFacadeInterface
{
    /**
     * Specification:
     * - Returns UserTransfer with an agent.
     * - If username is not exist, null will be returned.
     *
     * @api
     *
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findAgentByUsername(string $username): ?UserTransfer;

    /**
     * Specification:
     * - Returns CustomerAutocompleteResponseTransfer with list of customers found by query.
     * - Search works by first name, last name and email.
     * - If customers by query are not exist, collection will be empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerQueryTransfer $customerQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer
     */
    public function findCustomersByQuery(CustomerQueryTransfer $customerQueryTransfer): CustomerAutocompleteResponseTransfer;
}

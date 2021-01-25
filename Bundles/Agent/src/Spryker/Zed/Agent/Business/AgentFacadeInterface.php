<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\FindAgentResponseTransfer;

interface AgentFacadeInterface
{
    /**
     * Specification:
     * - Returns FindAgentResponseTransfer with an agent inside.
     * - If username is not exist, FindAgentResponseTransfer::isAgentFound will be false.
     *
     * @api
     *
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\FindAgentResponseTransfer
     */
    public function findAgentByUsername(string $username): FindAgentResponseTransfer;

    /**
     * Specification:
     * - Returns CustomerAutocompleteResponseTransfer with list of customers found by query.
     * - Search matches by partial first name, last name, email or exact customer reference.
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

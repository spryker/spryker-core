<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AgentClientInterface
{
    /**
     * Specification:
     * - Returns UserTransfer with an agent.
     * - If username is not exist, null will be returned.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findAgentByUsername(UserTransfer $userTransfer): ?UserTransfer;

    /**
     * Specification:
     * - Returns true if agent auth data exist in session storage.
     *
     * @api
     *
     * @return bool
     */
    public function isLoggedIn(): bool;

    /**
     * Specification:
     * - Returns UserTransfer of agent which logged in.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getAgent(): UserTransfer;

    /**
     * Specification:
     * - Saves UserTransfer into agent's session storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function setAgent(UserTransfer $userTransfer): void;

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

    /**
     * Specification:
     * - Sanitizes data related to the end of customer impersonation.
     * - Executes CustomerImpersonationSanitizerPluginInterface plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function sanitizeCustomerImpersonation(CustomerTransfer $customerTransfer): void;
}

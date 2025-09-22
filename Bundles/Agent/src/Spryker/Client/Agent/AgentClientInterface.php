<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AgentClientInterface
{
    /**
     * Specification:
     * - Returns UserTransfer with an agent.
     * - If username is not exist, null will be returned.
     *
     * @api
     */
    public function findAgentByUsername(UserTransfer $userTransfer): ?UserTransfer;

    /**
     * Specification:
     * - Returns true if agent auth data exist in session storage.
     *
     * @api
     */
    public function isLoggedIn(): bool;

    /**
     * Specification:
     * - Returns UserTransfer of agent which logged in.
     *
     * @api
     */
    public function getAgent(): UserTransfer;

    /**
     * Specification:
     * - Saves UserTransfer into agent's session storage.
     *
     * @api
     */
    public function setAgent(UserTransfer $userTransfer): void;

    /**
     * Specification:
     * - Invalidates agent session.
     *
     * @api
     */
    public function invalidateAgentSession(): void;

    /**
     * Specification:
     * - Returns CustomerAutocompleteResponseTransfer with list of customers found by query.
     * - Search matches by partial first name, last name, email or exact customer reference.
     * - If customers by query are not exist, collection will be empty.
     *
     * @api
     */
    public function findCustomersByQuery(CustomerQueryTransfer $customerQueryTransfer): CustomerAutocompleteResponseTransfer;

    /**
     * Specification:
     * - Executes ImpersonationSessionFinisherPluginInterface plugins.
     * - Removes customer information from session.
     *
     * @api
     */
    public function finishImpersonationSession(): void;

    /**
     * Specification:
     * - Reads a list of allowed patterns for an agent from the module's configuration.
     * - Modifies secured pattern based on a list.
     *
     * @api
     */
    public function applyAgentAccessOnSecuredPattern(string $securedPattern): string;
}

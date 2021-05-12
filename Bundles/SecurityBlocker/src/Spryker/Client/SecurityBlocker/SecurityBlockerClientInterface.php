<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;

interface SecurityBlockerClientInterface
{
    /**
     * Specification:
     * - Saves a failed login attempt based on the data provided in the `SecurityCheckAuthContextTransfer`.
     * - Returns `isBlocked=true` if the account has exceeded the configured number of login attempts and any further attempt to log in will be blocked.
     * - Returns `isBlocked=false` if the account has not yet exceeded the allowed number of attempts to login.
     * - The TTL and number of attempts configuration for storing records are provided per type of the entity.
     * - Requires the `SecurityCheckAuthContextTransfer.type` to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @throws \Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer
     */
    public function incrementLoginAttemptCount(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer;

    /**
     * Specification:
     * - Gets account blocking status based on the data provided in the `SecurityCheckAuthContextTransfer`.
     * - Returns `isBlocked=true` if the account has exceeded the configured number of login attempts.
     * - Returns `isBlocked=false` if the account has not yet exceeded the allowed number of attempts to login.
     * - The TTL and number of attempts configuration for the decision are provided per type of the entity.
     * - Requires the `SecurityCheckAuthContextTransfer.type` to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer
     */
    public function isAccountBlocked(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer;
}

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
     * - Returns `isSuccessful` to indicate whether the account has exceeded the configured number of login attempts.
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
    public function incrementLoginAttempt(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer;

    /**
     * Specification:
     * - Gets failed login attempt based on the data provided in the `SecurityCheckAuthContextTransfer`.
     * - Returns `isSuccessful` to indicate whether the account is blocked.
     * - The TTL and number of attempts configuration for the decision are provided per type of the entity.
     * - Requires the `SecurityCheckAuthContextTransfer.type` to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer
     */
    public function getLoginAttempt(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer;
}

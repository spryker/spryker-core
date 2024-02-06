<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantAgent\Business;

use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AclMerchantAgentFacadeInterface
{
    /**
     * Specification:
     * - Checks if merchant agent ACL access checker strategy is applicable.
     * - Returns `true` if `UserTransfer.isMerchantAgent` === `true` and `ROLE_MERCHANT_AGENT` access is granted.
     * - Returns `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function isMerchantAgentAclAccessCheckerStrategyApplicable(
        UserTransfer $userTransfer,
        RuleTransfer $ruleTransfer
    ): bool;

    /**
     * Specification:
     * - Checks if ACL access must be provided for merchant agent.
     * - Uses {@link \Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig::getMerchantAgentAclBundleAllowedList()}
     * to determine the bundles which merchant agent has ACL access to.
     * - Returns `true` for bundles listed in the config, returns `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function checkMerchantAgentAclAccess(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool;
}

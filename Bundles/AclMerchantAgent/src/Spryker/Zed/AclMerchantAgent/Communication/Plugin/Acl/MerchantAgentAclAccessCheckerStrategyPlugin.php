<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantAgent\Communication\Plugin\Acl;

use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AclExtension\Dependency\Plugin\AclAccessCheckerStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AclMerchantAgent\Business\AclMerchantAgentFacadeInterface getFacade()
 * @method \Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig getConfig()
 */
class MerchantAgentAclAccessCheckerStrategyPlugin extends AbstractPlugin implements AclAccessCheckerStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the merchant agent ACL access checker strategy is applicable for the given user and rule.
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
    public function isApplicable(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool
    {
        return $this->getFacade()->isMerchantAgentAclAccessCheckerStrategyApplicable($userTransfer, $ruleTransfer);
    }

    /**
     * {@inheritDoc}
     * - Checks if ACL access should be granted for the given merchant agent.
     * - Uses {@link \Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig::getMerchantAgentAclBundleAllowedList()}
     *  to determine the bundles which merchant agent has ACL access to.
     * - Returns `true` for bundles listed in the config, returns `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function checkAccess(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool
    {
        return $this->getFacade()->checkMerchantAgentAclAccess($userTransfer, $ruleTransfer);
    }
}

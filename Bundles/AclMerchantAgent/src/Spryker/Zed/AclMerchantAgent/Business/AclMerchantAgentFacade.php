<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantAgent\Business;

use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AclMerchantAgent\Business\AclMerchantAgentBusinessFactory getFactory()
 */
class AclMerchantAgentFacade extends AbstractFacade implements AclMerchantAgentFacadeInterface
{
    /**
     * {@inheritDoc}
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
    ): bool {
        return $this->getFactory()->createMerchantAgentAclAccessChecker()->isApplicable($userTransfer, $ruleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function checkMerchantAgentAclAccess(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool
    {
        return $this->getFactory()->createMerchantAgentAclAccessChecker()->checkAccess($userTransfer, $ruleTransfer);
    }
}

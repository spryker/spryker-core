<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantAgent\Business\Checker;

use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MerchantAgentAclAccessCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function isApplicable(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function checkAccess(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool;
}

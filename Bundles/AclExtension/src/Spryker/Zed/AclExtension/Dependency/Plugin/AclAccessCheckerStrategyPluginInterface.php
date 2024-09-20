<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;

/**
 * Use this plugin to implement access control strategies for ACL checks.
 */
interface AclAccessCheckerStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable to execute.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function isApplicable(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool;

    /**
     * Specification:
     * - Checks if the given user has access according to the given rule.
     * - Returns `true` if access should be granted, otherwise returns `false`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function checkAccess(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool;
}

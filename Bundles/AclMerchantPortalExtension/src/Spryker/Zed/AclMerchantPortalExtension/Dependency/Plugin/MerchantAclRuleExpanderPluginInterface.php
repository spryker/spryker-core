<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin;

/**
 * Provides capabilities to expand list of `AclRules`.
 *
 * Use this plugin if some default/predefined `AclRule` list need to be expanded.
 */
interface MerchantAclRuleExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands set of `AclRule` to assigned for merchant `AclRole`.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\RuleTransfer> $ruleTransfers
     *
     * @return list<\Generated\Shared\Transfer\RuleTransfer>
     */
    public function expand(array $ruleTransfers): array;
}

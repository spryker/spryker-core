<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin;

/**
 * Provides capabilities to expand list of `AclEntityRule`.
 *
 * Use this plugin if some default/predefined `AclEntityRule` list need to be expanded.
 */
interface MerchantAclEntityRuleExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands set of `AclEntityRule` to assigned for merchant `AclRole`.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function expand(array $aclEntityRuleTransfers): array;
}

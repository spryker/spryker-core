<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Creator;

use Generated\Shared\Transfer\RoleTransfer;

interface AclRuleCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return list<\Generated\Shared\Transfer\RuleTransfer>
     */
    public function createMerchantAclRules(RoleTransfer $roleTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return list<\Generated\Shared\Transfer\RuleTransfer>
     */
    public function createMerchantUserAclRules(RoleTransfer $roleTransfer): array;
}

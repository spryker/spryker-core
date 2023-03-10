<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Creator;

use Generated\Shared\Transfer\AclEntitySegmentTransfer;
use Generated\Shared\Transfer\RoleTransfer;

interface AclEntityRuleCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function createMerchantAclEntityRules(
        RoleTransfer $roleTransfer,
        AclEntitySegmentTransfer $aclEntitySegmentTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function createMerchantUserAclEntityRules(
        RoleTransfer $roleTransfer,
        AclEntitySegmentTransfer $aclEntitySegmentTransfer
    ): array;
}

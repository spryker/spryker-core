<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RoleTransfer;

interface AclEntityMapperInterface
{
    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\AclEntityRuleTransfer>
     *
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\AclEntityRuleTransfer[] $aclEntityRuleTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\AclEntityRuleTransfer[]
     */
    public function mapRoleTransferToAclEntityRuleTransfers(RoleTransfer $roleTransfer, ArrayObject $aclEntityRuleTransfers): ArrayObject;
}

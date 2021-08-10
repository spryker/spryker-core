<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;

interface AclEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function createGroup(GroupTransfer $groupTransfer): GroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function createRole(RoleTransfer $roleTransfer): RoleTransfer;
}

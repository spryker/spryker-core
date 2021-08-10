<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RoleTransfer;

/**
 * Plugin is triggered after ACL Role is created.
 */
interface AclRolePostSavePluginInterface
{
    /**
     * Specification:
     * - Runs after ACL Role is created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function postSave(RoleTransfer $roleTransfer): RoleTransfer;
}

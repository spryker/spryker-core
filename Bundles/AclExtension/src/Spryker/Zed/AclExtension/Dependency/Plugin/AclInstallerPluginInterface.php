<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclExtension\Dependency\Plugin;

/**
 * Specification:
 * - Executed by @link \Spryker\Zed\Acl\Business\AclFacadeInterface::install()}.
 * - Provides required for project ACL Roles and Groups.
 */
interface AclInstallerPluginInterface
{
    /**
     * Specification:
     * - Returns Roles with Rules to create on install.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function getRoles(): array;

    /**
     * Specification:
     * - Returns Groups to create on install.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getGroups(): array;
}

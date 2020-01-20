<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclExtension\Dependency\Plugin;

/**
 * Specification:
 * - Executed on install
 * - Use this plugin if default Roles or Groups are needed in project
 * - Use this plugin to assign Groups and Roles to existing users
 * - Do not use this plugin to create users
 * - The plugin methods are run in the following way: getGroups, getRoles and getUsers.
 */
interface AclInstallerPluginInterface
{
    /**
     * Specification:
     * - Returns Roles with Rules to create on install
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function getRoles(): array;

    /**
     * Specification:
     * - Returns Groups to create on install
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getGroups(): array;

    /**
     * Specification:
     * - List of existing users with Groups to assign
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\UserTransfer[]
     */
    public function getUsers(): array;
}

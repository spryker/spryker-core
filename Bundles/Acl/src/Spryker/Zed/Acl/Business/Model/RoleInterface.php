<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RoleTransfer;

interface RoleInterface
{
    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNameExistsException
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function addRole($name);

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $data
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNameExistsException
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNotFoundException
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function save(RoleTransfer $data);

    /**
     * @param int $idRole
     *
     * @return bool
     */
    public function hasRoleId($idRole);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasRoleName($name);

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getUserRoles($idUser);

    /**
     * @param int $idGroup
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getGroupRoles($idGroup);

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleById($id);

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RoleTransfer|null
     */
    public function findRoleById(int $id): ?RoleTransfer;

    /**
     * @param int $idRole
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNotFoundException
     *
     * @return bool
     */
    public function removeRoleById($idRole);

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getByName($name);
}

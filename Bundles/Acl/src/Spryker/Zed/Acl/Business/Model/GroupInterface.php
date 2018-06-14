<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupTransfer;

interface GroupInterface
{
    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function addGroup($name);

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function updateGroup(GroupTransfer $group);

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNameExistsException
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function save(GroupTransfer $group);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroup
     */
    public function getEntityGroupById($id);

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return void
     */
    public function removeUser($idGroup, $idUser);

    /**
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups();

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNameExistsException
     *
     * @return void
     */
    public function assertGroupHasName(GroupTransfer $group);

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return void
     */
    public function assertGroupExists(GroupTransfer $group);

    /**
     * @param int $idGroup
     *
     * @return bool
     */
    public function hasGroup($idGroup);

    /**
     * @param int $name
     *
     * @return bool
     */
    public function hasGroupName($name);

    /**
     * @param int $idGroup
     * @param int $idRole
     *
     * @return bool
     */
    public function hasRole($idGroup, $idRole);

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return bool
     */
    public function hasUser($idGroup, $idUser);

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser);

    /**
     * @param int $idRole
     * @param int $idGroup
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException
     *
     * @return int
     */
    public function addRoleToGroup($idRole, $idGroup);

    /**
     * @param int $idAclGroup
     *
     * @return void
     */
    public function removeRolesFromGroup($idAclGroup);

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException
     *
     * @return int
     */
    public function addUser($idGroup, $idUser);

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getByName($name);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroupById($id);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return bool
     */
    public function removeGroupById($id);

    /**
     * @param int $idGroup
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getRoles($idGroup);
}

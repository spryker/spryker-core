<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RoleTransfer;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNameExistsException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNotFoundException;

interface RoleInterface
{
    /**
     * @param $name
     * @param $idGroup
     *
     * @return mixed
     */
    public function addRole($name, $idGroup);

    /**
     * @param RoleTransfer $data
     *
     * @return RoleTransfer
     * @throws RoleNameExistsException
     * @throws RoleNotFoundException
    d     */
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
     * @return RoleTransfer
     */
    public function getUserRoles($idUser);

    /**
     * @param int $idGroup
     *
     * @return RoleTransfer
     * @throws GroupNotFoundException
     */
    public function getGroupRoles($idGroup);

    /**
     * @param int $id
     *
     * @return RoleTransfer
     */
    public function getRoleById($id);

    /**
     * @param int $id
     *
     * @return bool
     * @throws RoleNotFoundException
     */
    public function removeRoleById($id);

    /**
     * @param string $name
     *
     * @return RoleTransfer
     */
    public function getByName($name);
}

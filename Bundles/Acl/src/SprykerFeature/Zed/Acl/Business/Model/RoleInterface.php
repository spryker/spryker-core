<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RoleTransfer;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNameExistsException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNotFoundException;

interface RoleInterface
{

    /**
     * @param string $name
     * @param int $idGroup
     *
     * @throws RoleNameExistsException
     *
     * @return RoleTransfer
     */
    public function addRole($name, $idGroup);

    /**
     * @param RoleTransfer $data
     *
     * @throws RoleNameExistsException
     * @throws RoleNotFoundException
     *
     * @return RoleTransfer
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
     * @return RoleTransfer
     */
    public function getUserRoles($idUser);

    /**
     * @param int $idGroup
     *
     * @throws GroupNotFoundException
     *
     * @return RoleTransfer
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
     * @throws RoleNotFoundException
     *
     * @return bool
     */
    public function removeRoleById($id);

    /**
     * @param string $name
     *
     * @return RoleTransfer
     */
    public function getByName($name);

}

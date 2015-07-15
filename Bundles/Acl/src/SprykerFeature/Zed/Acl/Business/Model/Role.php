<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RolesTransfer;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclRole;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use Generated\Shared\Transfer\RoleTransfer;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNameExistsException;

class Role implements RoleInterface
{

    /**
     * @var Group
     */
    protected $queryContainer;

    /**
     * @var GroupInterface
     */
    private $group;

    /**
     * @param GroupInterface $group
     * @param AclQueryContainer $queryContainer
     */
    public function __construct(GroupInterface $group, AclQueryContainer $queryContainer)
    {
        $this->group = $group;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $name
     * @param int $idGroup
     *
     * @throws RoleNameExistsException
     *
     * @return RoleTransfer
     */
    public function addRole($name, $idGroup)
    {
        $data = new RoleTransfer();
        $data->setName($name);

        $role = $this->save($data);
        $role->setIdGroup($idGroup);

        $this->group->addRoleToGroup($role->getIdAclRole(), $idGroup);

        return $role;
    }

    /**
     * @param RoleTransfer $data
     *
     * @throws RoleNameExistsException
     * @throws RoleNotFoundException
     *
     * @return RoleTransfer
     */
    public function save(RoleTransfer $data)
    {
        $entity = new SpyAclRole();

        if ($data->getIdAclRole() !== null && $this->hasRoleId($data->getIdAclRole()) === true) {
            throw new RoleNotFoundException();
        }

        if ($this->hasRoleName($data->getName()) === true) {
            throw new RoleNameExistsException();
        }

        $entity->setName($data->getName());
        $entity->save();

        $transfer = new RoleTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $idRole
     *
     * @return bool
     */
    public function hasRoleId($idRole)
    {
        $entity = $this->queryContainer->queryRoleById($idRole)->count();

        return $entity > 0;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasRoleName($name)
    {
        $entity = $this->queryContainer->queryRoleByName($name)->count();

        return $entity > 0;
    }

    /**
     * @param int $idUser
     *
     * @return RoleTransfer
     */
    public function getUserRoles($idUser)
    {
        $group = $this->group->getUserGroup($idUser);

        return $this->getGroupRoles($group->getIdAclGroup());
    }

    /**
     * @param int $idGroup
     *
     * @throws GroupNotFoundException
     *
     * @return RolesTransfer
     */
    public function getGroupRoles($idGroup)
    {
        $results = $this->queryContainer->queryGroupRoles($idGroup)->find();

        $collection = new RolesTransfer();

        foreach ($results as $result) {
            $transfer = new RoleTransfer();
            Copy::entityToTransfer($transfer, $result);
            $collection->addRole($transfer);
        }

        return $collection;
    }

    /**
     * @param int $id
     *
     * @throws EmptyEntityException
     *
     * @return RoleTransfer
     */
    public function getRoleById($id)
    {
        $entity = $this->queryContainer->queryRoleById($id)->findOne();

        if ($entity === null) {
            throw new EmptyEntityException();
        }

        $transfer = new RoleTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $id
     *
     * @throws RoleNotFoundException
     *
     * @return bool
     */
    public function removeRoleById($id)
    {
        $entity = $this->queryContainer->queryRoleById($id)->delete();

        if ($entity <= 0) {
            throw new RoleNotFoundException();
        }

        return true;
    }

    /**
     * @param string $name
     *
     * @return RoleTransfer
     */
    public function getByName($name)
    {
        $entity = $this->queryContainer->queryRoleByName($name)->findOne();

        $transfer = new RoleTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

}

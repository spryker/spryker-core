<?php

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
     * @var AclQueryContainer
     */
    protected $queryContainer;

    /**
     * @var GroupInterface
     */
    private $groupModel;

    /**
     * @param GroupInterface $groupModel
     * @param AclQueryContainer $queryContainer
     */
    public function __construct(GroupInterface $groupModel, AclQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
        $this->groupModel = $groupModel;
    }

    /**
     * @param string $name
     * @param int $idGroup
     *
     * @return RoleTransfer
     * @throws RoleNameExistsException
     */
    public function addRole($name, $idGroup)
    {
        $data = new RoleTransfer();
        $data->setName($name);

        $role = $this->save($data);
        $role->setIdGroup($idGroup);

        $this->groupModel->addRoleToGroup($role->getIdAclRole(), $idGroup);

        return $role;
    }

    /**
     * @param RoleTransfer $data
     *
     * @return RoleTransfer
     * @throws RoleNameExistsException
     * @throws RoleNotFoundException
d     */
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
        $group = $this->groupModel->getUserGroup($idUser);

        return $this->getGroupRoles($group->getIdAclGroup());
    }

    /**
     * @param int $idGroup
     *
     * @return RolesTransfer
     * @throws GroupNotFoundException
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
     * @return RoleTransfer
     * @throws EmptyEntityException
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
     * @return bool
     * @throws RoleNotFoundException
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

<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Shared\Acl\Transfer\RoleCollection;
use SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Shared\Acl\Transfer\Role as TransferRole;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNameExistsException;

class Role implements RoleInterface
{
    /**
     * @var AclQueryContainer
     */
    protected $queryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param AclQueryContainer $queryContainer
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(AclQueryContainer $queryContainer, LocatorLocatorInterface $locator)
    {
        $this->queryContainer = $queryContainer;
        $this->locator = $locator;
    }

    /**
     * @param string $name
     * @param int $idGroup
     *
     * @return TransferRole
     * @throws RoleNameExistsException
     */
    public function addRole($name, $idGroup)
    {
        $data = new \Generated\Shared\Transfer\AclRoleTransfer();
        $data->setName($name);

        $role = $this->save($data);
        $role->setIdGroup($idGroup);

        $this->locator->acl()->facade()->addRoleToGroup($role->getIdAclRole(), $idGroup);

        return $role;
    }

    /**
     * @param TransferRole $data
     *
     * @return TransferRole
     * @throws RoleNameExistsException
     * @throws RoleNotFoundException
d     */
    public function save(TransferRole $data)
    {
        $entity = $this->locator->acl()->entitySpyAclRole();

        if ($data->getIdAclRole() !== null && $this->hasRoleId($data->getIdAclRole()) === true) {
            throw new RoleNotFoundException();
        }

        if ($this->hasRoleName($data->getName()) === true) {
            throw new RoleNameExistsException();
        }

        $entity->setName($data->getName());
        $entity->save();

        $transfer = new \Generated\Shared\Transfer\AclRoleTransfer();
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
     * @return RoleCollection
     */
    public function getUserRoles($idUser)
    {
        $group = $this->locator->acl()->facade()->getUserGroup($idUser);

        return $this->getGroupRoles($group->getIdAclGroup());
    }

    /**
     * @param int $idGroup
     *
     * @return RoleCollection
     * @throws GroupNotFoundException
     */
    public function getGroupRoles($idGroup)
    {
        $results = $this->queryContainer->queryGroupRoles($idGroup)->find();

        $collection = $this->locator->acl()->transferRoleCollection();
        $collection = Copy::entityCollectionToTransferCollection($collection, $results, false);

        return $collection;
    }

    /**
     * @param int $id
     *
     * @return AbstractTransfer
     * @throws EmptyEntityException
     */
    public function getRoleById($id)
    {
        $entity = $this->queryContainer->queryRoleById($id)->findOne();

        if ($entity === null) {
            throw new EmptyEntityException();
        }

        $transfer = new \Generated\Shared\Transfer\AclRoleTransfer();
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
     * @return TransferRole
     */
    public function getByName($name)
    {
        $entity = $this->queryContainer->queryRoleByName($name)->findOne();

        $transfer = new \Generated\Shared\Transfer\AclRoleTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }
}

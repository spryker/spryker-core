<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

use SprykerFeature\Shared\Acl\Transfer\GroupCollection;
use SprykerFeature\Zed\Acl\Persistence\Propel\Base\SpyAclGroup;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Shared\Acl\Transfer\RoleCollection;
use SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Shared\Acl\Transfer\Group as TransferGroup;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNameExistsException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException;

class Group implements GroupInterface
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
     *
     * @return TransferGroup
     */
    public function addGroup($name)
    {
        $data = new \Generated\Shared\Transfer\AclGroupTransfer();
        $data->setName($name);

        $this->assertGroupHasName($data);

        return $this->save($data);
    }

    /**
     * @param TransferGroup $group
     *
     * @return TransferGroup
     */
    public function updateGroup(TransferGroup $group)
    {
        $original = $this->getGroupById($group->getIdAclGroup());

        if ($group->getName() !== $original->getName()) {
            $this->assertGroupHasName($group);
        }

        return $this->save($group);
    }

    /**
     * @param TransferGroup $group
     *
     * @return TransferGroup
     */
    public function save(TransferGroup $group)
    {
        $this->assertGroupExists($group);

        if ($group->getIdAclGroup() !== null) {
            $entity = $this->getEntityGroupById($group->getIdAclGroup());
        } else {
            $entity = $this->locator->acl()->entitySpyAclGroup();
        }

        $entity->setName($group->getName());
        $entity->save();

        $transfer = new \Generated\Shared\Transfer\AclGroupTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $id
     *
     * @return TransferGroup
     * @throws GroupNotFoundException
     */
    public function getEntityGroupById($id)
    {
        $entity = $this->queryContainer->queryGroupById($id)->findOne();

        if ($entity === null) {
            throw new GroupNotFoundException();
        }

        return $entity;
    }

    /**
     * @param int $idGroup
     *
     * @return bool
     */
    public function hasGroup($idGroup)
    {
        $amount = $this->queryContainer->queryGroupById($idGroup)->count();

        return $amount > 0;
    }

    /**
     * @param int $name
     *
     * @return bool
     */
    public function hasGroupName($name)
    {
        $amount = $this->queryContainer->queryGroupByName($name)->count();

        return $amount > 0;
    }

    /**
     * @param int $idGroup
     * @param int $idRole
     *
     * @return bool
     */
    public function hasRole($idGroup, $idRole)
    {
        $amount = $this->queryContainer->queryGroupHasRoleById($idGroup, $idRole)->count();

        return $amount > 0;
    }

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return bool
     */
    public function hasUser($idGroup, $idUser)
    {
        $amount = $this->queryContainer->queryUserHasGroupById($idGroup, $idUser)->count();

        return $amount > 0;
    }

    /**
     * @param int $idUser
     *
     * @return TransferGroup
     */
    public function getUserGroup($idUser)
    {
        $entity = $this->queryContainer->queryUserGroupByIdUser($idUser)->findOne();

        $transfer = new \Generated\Shared\Transfer\AclGroupTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $idGroup
     * @param int $idRole
     *
     * @return int
     * @throws GroupAlreadyHasRoleException
     */
    public function addRole($idGroup, $idRole)
    {
        if ($this->hasRole($idGroup, $idRole)) {
            throw new GroupAlreadyHasRoleException();
        }

        $entity = $this->locator
            ->acl()
            ->entitySpyAclGroupsHasRoles();

        $entity->setFkAclGroup($idGroup)
            ->setFkAclRole($idRole);

        return $entity->save();
    }

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return int
     * @throws GroupAlreadyHasRoleException
     */
    public function addUser($idGroup, $idUser)
    {
        if ($this->hasUser($idGroup, $idUser)) {
            throw new GroupAlreadyHasRoleException();
        }

        $entity = $this->locator
            ->acl()
            ->entitySpyAclUserHasGroup();

        $entity->setFkAclGroup($idGroup)
            ->setFkUserUser($idUser);

        return $entity->save();
    }

    /**
     * @param $idGroup
     * @param $idUser
     */
    public function removeUser($idGroup, $idUser)
    {
        $entity = $this->queryContainer->queryUserHasGroupById($idGroup, $idUser)->findOne();

        $entity->delete();
    }

    /**
     * @return GroupCollection
     */
    public function getAllGroups()
    {
        $collection = new \Generated\Shared\Transfer\AclGroupTransfer();

        $results = $this->queryContainer
            ->queryGroup()
            ->find();

        $collection = Copy::entityCollectionToTransferCollection($collection, $results, true);

        return $collection;
    }

    /**
     * @param string $name
     *
     * @return TransferGroup
     */
    public function getByName($name)
    {
        $entity = $this->queryContainer->queryGroupByName($name)->findOne();

        $transfer = new \Generated\Shared\Transfer\AclGroupTransfer();

        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $id
     *
     * @return TransferGroup
     * @throws GroupNotFoundException
     */
    public function getGroupById($id)
    {
        $entity = $this->getGroupEntityById($id);

        $transfer = new \Generated\Shared\Transfer\AclGroupTransfer();

        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws GroupNotFoundException
     */
    public function removeGroupById($id)
    {
        $entity = $this->queryContainer
            ->queryGroupById($id)
            ->delete();

        if ($entity <= 0) {
            throw new GroupNotFoundException();
        }

        return true;
    }

    /**
     * @param int $id
     *
     * @return SpyAclGroup
     * @throws EmptyEntityException
     */
    protected function getGroupEntityById($id)
    {
        $entity = $this->queryContainer->queryGroupById($id)->findOne();

        if ($entity === null) {
            throw new EmptyEntityException();
        }

        return $entity;
    }

    /**
     * @param int $idGroup
     *
     * @return RoleCollection
     * @throws GroupNotFoundException
     */
    public function getRoles($idGroup)
    {
        $results = $this->queryContainer
            ->queryGroupRoles($idGroup)
            ->find();

        $collection = new \Generated\Shared\Transfer\AclRoleTransfer();

        $collection = Copy::entityCollectionToTransferCollection($collection, $results, true);

        return $collection;
    }

    /**
     * @param TransferGroup $group
     *
     * @throws GroupNameExistsException
     */
    public function assertGroupHasName(transferGroup $group)
    {
        if ($this->hasGroupName($group->getName()) === true) {
            throw new GroupNameExistsException();
        }
    }

    /**
     * @param TransferGroup $group
     *
     * @throws GroupNotFoundException
     */
    public function assertGroupExists(transferGroup $group)
    {
        if ($group->getIdAclGroup() !== null && $this->hasGroup($group->getIdAclGroup()) === false) {
            throw new GroupNotFoundException();
        }
    }
}

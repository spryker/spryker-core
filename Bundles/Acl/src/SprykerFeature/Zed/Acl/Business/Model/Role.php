<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RolesTransfer;
use SprykerFeature\Zed\Acl\AclConfig;
use SprykerFeature\Zed\Acl\Business\Exception\RootNodeModificationException;
use Orm\Zed\Acl\Persistence\SpyAclRole;
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
     *
     * @throws RoleNameExistsException
     *
     * @return RoleTransfer
     */
    public function addRole($name)
    {
        $data = new RoleTransfer();
        $data->setName($name);

        $role = $this->save($data);

        return $role;
    }

    /**
     * @param RoleTransfer $roleTransfer
     *
     * @throws RoleNameExistsException
     * @throws RootNodeModificationException
     *
     * @return RoleTransfer
     */
    public function save(RoleTransfer $roleTransfer)
    {
        $aclRoleEntity = new SpyAclRole();
        if (!empty($roleTransfer->getIdAclRole())) {
            $aclRoleEntity = $this->queryContainer->queryRoleById($roleTransfer->getIdAclRole())->findOne();
            if ($aclRoleEntity->getName() === AclConfig::ROOT_ROLE) {
                throw new RootNodeModificationException('Could not modify root role node!');
            }
        }

        if ($this->hasRoleName($roleTransfer->getName())) {
            throw new RoleNameExistsException(
                sprintf('Role with name "%s" already exists!', $roleTransfer->getName())
            );
        }

        $aclRoleEntity->setName($roleTransfer->getName());
        $aclRoleEntity->save();

        $roleTransfer = new RoleTransfer();
        $roleTransfer = Copy::entityToTransfer($roleTransfer, $aclRoleEntity);

        return $roleTransfer;
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
        $aclRoleEntity = $this->queryContainer->queryRoleByName($name)->count();

        return $aclRoleEntity > 0;
    }

    /**
     * @param int $idUser
     *
     * @return RoleTransfer
     */
    public function getUserRoles($idUser)
    {
        $groupTransfer = $this->group->getUserGroup($idUser);

        return $this->getGroupRoles($groupTransfer->getIdAclGroup());
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
        $aclRoleEntity = $this->queryContainer->queryGroupRoles($idGroup)->find();

        $rolesTransfer = new RolesTransfer();

        foreach ($aclRoleEntity as $result) {
            $roleTransfer = new RoleTransfer();
            Copy::entityToTransfer($roleTransfer, $result);
            $rolesTransfer->addRole($roleTransfer);
        }

        return $rolesTransfer;
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
        $aclRoleEntity = $this->queryContainer->queryRoleById($id)->findOne();

        if ($aclRoleEntity === null) {
            throw new EmptyEntityException();
        }

        $roleTransfer = new RoleTransfer();
        $roleTransfer = Copy::entityToTransfer($roleTransfer, $aclRoleEntity);

        return $roleTransfer;
    }

    /**
     * @param int $idRole
     *
     * @throws RoleNotFoundException
     *
     * @return bool
     */
    public function removeRoleById($idRole)
    {
        $aclRules = $this->queryContainer->queryRuleByRoleId($idRole)->find();
        $aclRules->delete();

        $aclRoleEntity = $this->queryContainer->queryRoleById($idRole)->delete();

        if ($aclRoleEntity <= 0) {
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
        $aclRoleEntity = $this->queryContainer->queryRoleByName($name)->findOne();

        $roleTransfer = new RoleTransfer();
        $roleTransfer = Copy::entityToTransfer($roleTransfer, $aclRoleEntity);

        return $roleTransfer;
    }

}

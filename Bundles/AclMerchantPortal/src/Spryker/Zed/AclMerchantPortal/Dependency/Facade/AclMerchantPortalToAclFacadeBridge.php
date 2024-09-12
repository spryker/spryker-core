<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Dependency\Facade;

use Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer;
use Generated\Shared\Transfer\AclUserHasGroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupsTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;

class AclMerchantPortalToAclFacadeBridge implements AclMerchantPortalToAclFacadeInterface
{
    /**
     * @var \Spryker\Zed\Acl\Business\AclFacadeInterface
     */
    protected $aclFacade;

    /**
     * @param \Spryker\Zed\Acl\Business\AclFacadeInterface $aclFacade
     */
    public function __construct($aclFacade)
    {
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function addRule(RuleTransfer $ruleTransfer): RuleTransfer
    {
        return $this->aclFacade->addRule($ruleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function createGroup(GroupTransfer $groupTransfer, RolesTransfer $rolesTransfer): GroupTransfer
    {
        return $this->aclFacade->createGroup($groupTransfer, $rolesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function createRole(RoleTransfer $roleTransfer): RoleTransfer
    {
        return $this->aclFacade->createRole($roleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupCriteriaTransfer $groupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    public function findGroup(GroupCriteriaTransfer $groupCriteriaTransfer): ?GroupTransfer
    {
        return $this->aclFacade->findGroup($groupCriteriaTransfer);
    }

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup(int $idUser, int $idGroup): int
    {
        return $this->aclFacade->addUserToGroup($idUser, $idGroup);
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups(int $idUser): GroupsTransfer
    {
        return $this->aclFacade->getUserGroups($idUser);
    }

    /**
     * @param \Generated\Shared\Transfer\AclUserHasGroupCriteriaTransfer $aclUserHasGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer
     */
    public function getAclUserHasGroupCollection(
        AclUserHasGroupCriteriaTransfer $aclUserHasGroupCriteriaTransfer
    ): AclUserHasGroupCollectionTransfer {
        return $this->aclFacade->getAclUserHasGroupCollection($aclUserHasGroupCriteriaTransfer);
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleByName(string $name): RoleTransfer
    {
        return $this->aclFacade->getRoleByName($name);
    }

    /**
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param string $type
     *
     * @return bool
     */
    public function existsRoleRule(
        int $idAclRole,
        string $bundle,
        string $controller,
        string $action,
        string $type
    ): bool {
        return $this->aclFacade->existsRoleRule($idAclRole, $bundle, $controller, $action, $type);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $transfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function updateGroup(GroupTransfer $transfer, RolesTransfer $rolesTransfer): GroupTransfer
    {
        return $this->aclFacade->updateGroup($transfer, $rolesTransfer);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsRoleByName(string $name): bool
    {
        return $this->aclFacade->existsRoleByName($name);
    }

    /**
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getGroupRoles(int $idGroup): RolesTransfer
    {
        return $this->aclFacade->getGroupRoles($idGroup);
    }
}

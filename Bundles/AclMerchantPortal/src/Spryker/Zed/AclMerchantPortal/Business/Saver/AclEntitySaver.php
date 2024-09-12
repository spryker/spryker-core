<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Saver;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\AclMerchantPortal\Business\Adder\GroupAdderInterface;
use Spryker\Zed\AclMerchantPortal\Business\Checker\AclRoleAssignmentCheckerInterface;
use Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;

class AclEntitySaver implements AclEntitySaverInterface
{
    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    protected AclMerchantPortalToAclFacadeInterface $aclFacade;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface
     */
    protected AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Adder\GroupAdderInterface
     */
    protected GroupAdderInterface $groupAdder;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Saver\AclRoleSaverInterface
     */
    protected AclRoleSaverInterface $aclRoleSaver;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Checker\AclRoleAssignmentCheckerInterface
     */
    protected AclRoleAssignmentCheckerInterface $aclRoleAssignmentChecker;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface $aclFacade
     * @param \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator
     * @param \Spryker\Zed\AclMerchantPortal\Business\Saver\AclRoleSaverInterface $aclRoleSaver
     * @param \Spryker\Zed\AclMerchantPortal\Business\Adder\GroupAdderInterface $groupAdder
     * @param \Spryker\Zed\AclMerchantPortal\Business\Checker\AclRoleAssignmentCheckerInterface $aclRoleAssignmentChecker
     */
    public function __construct(
        AclMerchantPortalToAclFacadeInterface $aclFacade,
        AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator,
        AclRoleSaverInterface $aclRoleSaver,
        GroupAdderInterface $groupAdder,
        AclRoleAssignmentCheckerInterface $aclRoleAssignmentChecker
    ) {
        $this->aclFacade = $aclFacade;
        $this->aclMerchantPortalGenerator = $aclMerchantPortalGenerator;
        $this->aclRoleSaver = $aclRoleSaver;
        $this->groupAdder = $groupAdder;
        $this->aclRoleAssignmentChecker = $aclRoleAssignmentChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function saveAclEntitiesForMerchant(MerchantTransfer $merchantTransfer): void
    {
        $merchantAclRoleTransfer = $this->aclRoleSaver->saveMerchantAclRole($merchantTransfer);
        $rolesTransfer = (new RolesTransfer())
            ->addRole($merchantAclRoleTransfer);

        $groupTransfer = (new GroupTransfer())
            ->setName($this->aclMerchantPortalGenerator->generateAclMerchantGroupName($merchantTransfer))
            ->setReference($this->aclMerchantPortalGenerator->generateAclMerchantReference($merchantTransfer));

        $existingGroup = $this->findExistingGroup($groupTransfer->getReferenceOrFail());
        if ($existingGroup) {
            $this->addRoleToGroupIfNotAssigned($existingGroup, $rolesTransfer, $merchantAclRoleTransfer);

            return;
        }

        $this->aclFacade->createGroup($groupTransfer, $rolesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    public function saveAclEntitiesForMerchantUser(MerchantUserTransfer $merchantUserTransfer): void
    {
        $merchantUserAclRoleTransfer = $this->aclRoleSaver->saveMerchantUserAclRole($merchantUserTransfer);
        $rolesTransfer = (new RolesTransfer())
            ->addRole($merchantUserAclRoleTransfer);

        $groupTransfer = (new GroupTransfer())
            ->setName($this->aclMerchantPortalGenerator->generateAclMerchantUserGroupName($merchantUserTransfer))
            ->setReference($this->aclMerchantPortalGenerator->generateAclMerchantUserReference($merchantUserTransfer->getUserOrFail()));

        $existingGroup = $this->findExistingGroup($groupTransfer->getReferenceOrFail());
        if ($existingGroup) {
            $merchantUserGroupTransfer = $this->addRoleToGroupIfNotAssigned($existingGroup, $rolesTransfer, $merchantUserAclRoleTransfer);
            $this->groupAdder->addMerchantUserToGroups($merchantUserTransfer, $merchantUserGroupTransfer);

            return;
        }

        $merchantUserGroupTransfer = $this->aclFacade->createGroup($groupTransfer, $rolesTransfer);
        $this->groupAdder->addMerchantUserToGroups($merchantUserTransfer, $merchantUserGroupTransfer);
    }

    /**
     * @param string $reference
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    protected function findExistingGroup(string $reference): ?GroupTransfer
    {
        $groupCriteriaTransfer = (new GroupCriteriaTransfer())->setReference($reference);

        return $this->aclFacade->findGroup($groupCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    protected function addRoleToGroupIfNotAssigned(
        GroupTransfer $groupTransfer,
        RolesTransfer $rolesTransfer,
        RoleTransfer $roleTransfer
    ): GroupTransfer {
        if (!$this->aclRoleAssignmentChecker->isRoleAssignedToGroup($groupTransfer, $roleTransfer)) {
            $groupTransfer = $this->aclFacade->updateGroup($groupTransfer, $rolesTransfer);
        }

        return $groupTransfer;
    }
}

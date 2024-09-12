<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Checker;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;

class AclRoleAssignmentChecker implements AclRoleAssignmentCheckerInterface
{
    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    protected AclMerchantPortalToAclFacadeInterface $aclFacade;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface $aclFacade
     */
    public function __construct(AclMerchantPortalToAclFacadeInterface $aclFacade)
    {
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return bool
     */
    public function isRoleAssignedToGroup(GroupTransfer $groupTransfer, RoleTransfer $roleTransfer): bool
    {
        $existingGroupRoles = $this->aclFacade->getGroupRoles($groupTransfer->getIdAclGroupOrFail());
        foreach ($existingGroupRoles->getRoles() as $existingRoleTransfer) {
            if ($existingRoleTransfer->getIdAclRole() === $roleTransfer->getIdAclRole()) {
                return true;
            }
        }

        return false;
    }
}

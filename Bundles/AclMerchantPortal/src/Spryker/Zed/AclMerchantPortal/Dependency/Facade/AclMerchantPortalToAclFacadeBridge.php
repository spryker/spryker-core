<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Dependency\Facade;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
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
    public function addRule(RuleTransfer $ruleTransfer)
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
    public function addUserToGroup($idUser, $idGroup)
    {
        return $this->aclFacade->addUserToGroup($idUser, $idGroup);
    }
}

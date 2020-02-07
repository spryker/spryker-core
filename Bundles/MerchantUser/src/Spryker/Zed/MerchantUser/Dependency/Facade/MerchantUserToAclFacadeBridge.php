<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Dependency\Facade;

use Generated\Shared\Transfer\GroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\GroupTransfer;

class MerchantUserToAclFacadeBridge implements MerchantUserToAclFacadeInterface
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
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup($idUser, $idGroup)
    {
        return $this->aclFacade->addUserToGroup($idUser, $idGroup);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupCriteriaFilterTransfer $groupCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    public function findGroup(GroupCriteriaFilterTransfer $groupCriteriaFilterTransfer): ?GroupTransfer
    {
        return $this->aclFacade->findGroup($groupCriteriaFilterTransfer);
    }
}

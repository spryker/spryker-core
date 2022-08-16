<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Checker;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;

class MerchantUserRestrictionChecker implements MerchantUserRestrictionCheckerInterface
{
    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ROOT_GROUP
     *
     * @var string
     */
    protected const ROOT_GROUP_NAME = 'root_group';

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
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return bool
     */
    public function isLoginRestricted(MerchantUserTransfer $merchantUserTransfer): bool
    {
        $groupsTransfer = $this->aclFacade->getUserGroups($merchantUserTransfer->getIdUserOrFail());
        foreach ($groupsTransfer->getGroups() as $groupTransfer) {
            if ($groupTransfer->getName() === static::ROOT_GROUP_NAME) {
                return true;
            }
        }

        return false;
    }
}

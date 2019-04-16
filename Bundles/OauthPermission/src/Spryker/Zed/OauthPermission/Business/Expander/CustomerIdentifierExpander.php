<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business\Expander;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface;

class CustomerIdentifierExpander implements CustomerIdentifierExpanderInterface
{
    /**
     * @var \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface $permissionFacade
     */
    public function __construct(OauthPermissionToPermissionFacadeInterface $permissionFacade)
    {
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifier(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        if (!$customerIdentifierTransfer->getIdCompanyUser()) {
            return $customerIdentifierTransfer;
        }

        $customerIdentifierTransfer->setPermissions(
            $this->permissionFacade->getPermissionsByIdentifier($customerIdentifierTransfer->getIdCompanyUser())
        );

        return $customerIdentifierTransfer;
    }
}

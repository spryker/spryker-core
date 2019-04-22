<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business\Expander;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToCompanyUserFacadeInterface;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface;

class CustomerIdentifierExpander implements CustomerIdentifierExpanderInterface
{
    /**
     * @var \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @var \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface $permissionFacade
     * @param \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        OauthPermissionToPermissionFacadeInterface $permissionFacade,
        OauthPermissionToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->permissionFacade = $permissionFacade;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifierWithPermissions(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        if (!$customerIdentifierTransfer->getIdCompanyUser()) {
            return $customerIdentifierTransfer;
        }

        $companyUserTransfer = $this->companyUserFacade->findActiveCompanyUserByUuid(
            (new CompanyUserTransfer())->setUuid($customerIdentifierTransfer->getIdCompanyUser())
        );

        if (!$companyUserTransfer) {
            return $customerIdentifierTransfer;
        }

        $customerIdentifierTransfer->setPermissions(
            $this->permissionFacade->getPermissionsByIdentifier((string)$companyUserTransfer->getIdCompanyUser())
        );

        return $customerIdentifierTransfer;
    }
}

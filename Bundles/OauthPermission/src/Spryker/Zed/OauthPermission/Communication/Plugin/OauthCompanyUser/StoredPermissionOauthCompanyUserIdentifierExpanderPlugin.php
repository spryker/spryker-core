<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Communication\Plugin\OauthCompanyUser;

use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\OauthCompanyUserIdentifierExpanderPluginInterface;

/**
 * @method \Spryker\Zed\OauthPermission\Business\OauthPermissionFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthPermission\OauthPermissionConfig getConfig()
 * @method \Spryker\Zed\OauthPermission\Communication\OauthPermissionCommunicationFactory getFactory()
 * @method \Spryker\Zed\OauthPermission\Business\OauthPermissionBusinessFactory getBusinessFactory()
 */
class StoredPermissionOauthCompanyUserIdentifierExpanderPlugin extends AbstractPlugin implements OauthCompanyUserIdentifierExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the CompanyUserIdentifierTransfer with permissions collection if idCompanyUser is set up in CompanyUserTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserIdentifierTransfer $companyUserIdentifierTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserIdentifierTransfer
     */
    public function expandCompanyUserIdentifier(
        CompanyUserIdentifierTransfer $companyUserIdentifierTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserIdentifierTransfer {
        return $this->getBusinessFactory()
            ->createCompanyUserPermissionStorage()
            ->storePermissions($companyUserIdentifierTransfer, $companyUserTransfer);
    }
}

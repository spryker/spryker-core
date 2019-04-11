<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Communication\Plugin\OauthCustomerConnector;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\OauthCompanyUserScopeProviderPluginInterface;

/**
 * @method \Spryker\Zed\Permission\PermissionConfig getConfig()
 * @method \Spryker\Zed\Permission\Business\PermissionFacadeInterface getFacade()
 */
class OauthCompanyUserPermissionScopeProviderPlugin extends AbstractPlugin implements OauthCompanyUserScopeProviderPluginInterface
{
    /**
     * TODO: Specs
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function provideScopes(CustomerIdentifierTransfer $customerIdentifierTransfer): array
    {
        $idCompanyUser = $customerIdentifierTransfer->getIdCompanyUser();
        $scopes = [];

        if (!$idCompanyUser) {
            return $scopes;
        }

        $permissionCollectionTransfer = $this->getFacade()
            ->getPermissionsByIdentifier($idCompanyUser);

        foreach ($permissionCollectionTransfer->getPermissions() as $permission) {
            $scopes[] = (new OauthScopeTransfer())->setIdentifier($permission->getKey());
        }

        return $scopes;
    }
}

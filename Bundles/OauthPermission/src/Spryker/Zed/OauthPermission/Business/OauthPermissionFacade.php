<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OauthPermission\Business\OauthPermissionBusinessFactory getFactory()
 */
class OauthPermissionFacade extends AbstractFacade implements OauthPermissionFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function provideScopesByCustomerIdentifier(CustomerIdentifierTransfer $customerIdentifierTransfer): array
    {
        $idCompanyUser = $customerIdentifierTransfer->getIdCompanyUser();
        $scopes = [];

        if (!$idCompanyUser) {
            return $scopes;
        }

        $permissionCollectionTransfer = $this->getFactory()
            ->getPermissionFacade()
            ->getPermissionsByIdentifier($idCompanyUser);

        $converter = $this->getFactory()->createOauthPermissionConverter();

        foreach ($permissionCollectionTransfer->getPermissions() as $permission) {
            $scopes[] = (new OauthScopeTransfer())->setIdentifier($converter->convertPermissionToScope($permission));
        }

        return $scopes;
    }
}

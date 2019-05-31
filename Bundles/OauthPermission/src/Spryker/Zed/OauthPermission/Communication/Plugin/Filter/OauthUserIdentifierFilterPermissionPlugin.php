<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Communication\Plugin\Filter;

use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\OauthCompanyUserIdentifierExpanderPluginInterface;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface;

/**
 * @method \Spryker\Zed\OauthPermission\Business\OauthPermissionFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthPermission\OauthPermissionConfig getConfig()
 * @method \Spryker\Zed\OauthPermission\Communication\OauthPermissionCommunicationFactory getFactory()
 */
class OauthUserIdentifierFilterPermissionPlugin extends AbstractPlugin implements OauthUserIdentifierFilterPluginInterface
{
    public function filter(array $userIdentifier): array
    {
        unset($userIdentifier['permissions']);

        return $userIdentifier;
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Permission;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface getFacade()
 */
class ManageCompanyUserInvitationPermissionPlugin extends AbstractPlugin implements PermissionPluginInterface
{
    public const KEY = 'ManageCompanyUserInvitationPermissionPlugin';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}

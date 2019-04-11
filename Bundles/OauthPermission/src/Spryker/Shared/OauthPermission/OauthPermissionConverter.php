<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthPermission;

use Generated\Shared\Transfer\PermissionTransfer;

class OauthPermissionConverter implements OauthPermissionConverterInterface
{
    private const PERMISSION_KEY = 'key';
    private const PERMISSION_CONFIGURATION = 'configuration';

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return string
     */
    public function convertPermissionToScope(PermissionTransfer $permissionTransfer): string
    {
        if ($permissionTransfer->getConfiguration()) {
            return json_encode([
                static::PERMISSION_KEY => $permissionTransfer->getKey(),
                static::PERMISSION_CONFIGURATION => $permissionTransfer->getConfiguration(),
            ]);
        }

        return $permissionTransfer->getKey();
    }

    /**
     * @param string $scope
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function convertScopeToPermission(string $scope): PermissionTransfer
    {
        $permissionTransfer = new PermissionTransfer();
        $permission = json_decode($scope, true);

        if (is_array($permission)) {
            $permissionTransfer->setKey($permission[static::PERMISSION_KEY]);
            $permissionTransfer->setConfiguration($permission[static::PERMISSION_CONFIGURATION]);

            return $permissionTransfer;
        }

        return $permissionTransfer->setKey($permission);
    }
}

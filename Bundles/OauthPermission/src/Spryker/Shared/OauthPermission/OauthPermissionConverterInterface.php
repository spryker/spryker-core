<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthPermission;

use Generated\Shared\Transfer\PermissionTransfer;

interface OauthPermissionConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return string
     */
    public function convertPermissionToScope(PermissionTransfer $permissionTransfer): string;

    /**
     * @param string $scope
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function convertScopeToPermission(string $scope): PermissionTransfer;
}

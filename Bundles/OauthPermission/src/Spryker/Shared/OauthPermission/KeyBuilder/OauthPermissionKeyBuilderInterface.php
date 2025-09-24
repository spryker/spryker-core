<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthPermission\KeyBuilder;

use Generated\Shared\Transfer\OauthPermissionStorageKeyTransfer;

interface OauthPermissionKeyBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthPermissionStorageKeyTransfer $oauthPermissionStorageKeyTransfer
     *
     * @return string
     */
    public function generateKey(OauthPermissionStorageKeyTransfer $oauthPermissionStorageKeyTransfer): string;
}

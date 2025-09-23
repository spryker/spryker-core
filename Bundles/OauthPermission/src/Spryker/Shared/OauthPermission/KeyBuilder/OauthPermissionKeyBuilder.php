<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthPermission\KeyBuilder;

use Generated\Shared\Transfer\OauthPermissionStorageKeyTransfer;

class OauthPermissionKeyBuilder implements OauthPermissionKeyBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_PREFIX = 'company_user_permissions';

    /**
     * @var string
     */
    protected const KEY_PATTERN = '%s_%s';

    /**
     * @param \Generated\Shared\Transfer\OauthPermissionStorageKeyTransfer $oauthPermissionStorageKeyTransfer
     *
     * @return string
     */
    public function generateKey(OauthPermissionStorageKeyTransfer $oauthPermissionStorageKeyTransfer): string
    {
        $oauthPermissionStorageKeyTransfer->requireIdCompanyUser();

        return sprintf(
            static::KEY_PATTERN,
            static::KEY_PREFIX,
            $oauthPermissionStorageKeyTransfer->getIdCompanyUser(),
        );
    }
}

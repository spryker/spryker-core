<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\OauthCompanyUser\OauthCompanyUserConfig getSharedConfig()
 */
class OauthCompanyUserConfig extends AbstractBundleConfig
{
    public const SCOPE_COMPANY_USER = 'company_user';

    public const GRANT_TYPE_ID_COMPANY_USER = 'idCompanyUser';

    /**
     * @return array
     */
    public function getCompanyUserScopes(): array
    {
        return [
            static::SCOPE_COMPANY_USER,
        ];
    }
}

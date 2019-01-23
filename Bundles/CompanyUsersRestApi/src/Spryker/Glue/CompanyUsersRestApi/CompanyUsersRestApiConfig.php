<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyUsersRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANY_USERS = 'company-users';
    public const CONTROLLER_RESOURCE_COMPANY_USERS = 'company-users-resource';

    public const RESOURCE_COMPANY_USERS_GET_ACTION_NAME = 'get';

    public const RESPONSE_HEADERS_MISSING_COMPANY_USER_CODE = '1401';
    public const RESPONSE_HEADERS_MISSING_COMPANY_USER = self::X_COMPANY_USER_ID_HEADER_KEY . ' header is missing.';
    public const RESPONSE_CODE_RESOURCE_NOT_IMPLEMENTED = '1402';
    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Resource is not implemented.';

    public const X_COMPANY_USER_ID_HEADER_KEY = 'X-Company-User-Id';

    /**
     * @type string[]
     */
    protected const COMPANY_USER_RESOURCES = [];

    /**
     * @return string[]
     */
    public function getCompanyUserResources(): array
    {
        return static::COMPANY_USER_RESOURCES;
    }
}

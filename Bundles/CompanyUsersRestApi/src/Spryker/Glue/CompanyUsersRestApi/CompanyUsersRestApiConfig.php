<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyUsersRestApiConfig extends AbstractBundleConfig
{
    public const X_COMPANY_USER_ID_HEADER_KEY = 'X-Company-User-Id';

    public const RESPONSE_HEADERS_MISSING_COMPANY_USER_CODE = '1401';
    public const RESPONSE_HEADERS_MISSING_COMPANY_USER = self::X_COMPANY_USER_ID_HEADER_KEY . ' header is missing.';

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

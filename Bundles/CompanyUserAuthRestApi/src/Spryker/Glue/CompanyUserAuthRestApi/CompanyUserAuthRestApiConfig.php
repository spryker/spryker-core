<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyUserAuthRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANY_USER_ACCESS_TOKENS = 'company-user-access-tokens';
    public const CONTROLLER_COMPANY_USER_ACCESS_TOKENS_RESOURCE = 'company-user-access-tokens-resource';

    public const CLIENT_GRANT_USER = 'idCompanyUser';

    public const RESPONSE_DETAIL_MISSING_ACCESS_TOKEN = 'Missing access token.';
    public const RESPONSE_DETAIL_INVALID_ACCESS_TOKEN = 'Invalid access token.';
    public const RESPONSE_DETAIL_INVALID_LOGIN = 'Failed to authenticate user.';

    public const RESPONSE_CODE_ACCESS_CODE_INVALID = '001';
    public const RESPONSE_CODE_FORBIDDEN = '002';
    public const RESPONSE_CODE_INVALID_LOGIN = '003';
}

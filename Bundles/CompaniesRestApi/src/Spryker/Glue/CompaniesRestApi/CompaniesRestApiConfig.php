<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompaniesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANIES = 'companies';
    public const CONTROLLER_RESOURCE_COMPANIES = 'companies-resource';

    /**
     * @deprecated Will be removed with next major release.
     */
    public const ACTION_COMPANIES_GET = 'get';

    public const RESPONSE_CODE_COMPANY_NOT_FOUND = '1801';
    public const RESPONSE_DETAIL_COMPANY_NOT_FOUND = 'Company not found.';

    public const RESPONSE_CODE_COMPANY_USER_NOT_SELECTED = '1803';
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_SELECTED = 'Current company user is not set. You need to select the current company user with /company-user-access-tokens in order to access the resource collection.';

    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Endpoint is not implemented.';

    /**
     * @uses \Spryker\Glue\GlueApplication\GlueApplicationConfig::COLLECTION_IDENTIFIER_CURRENT_USER
     */
    public const COLLECTION_IDENTIFIER_CURRENT_USER = 'mine';
}

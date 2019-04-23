<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyRolesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANY_ROLES = 'company-roles';
    public const CONTROLLER_RESOURCE_COMPANY_ROLES = 'company-roles-resource';

    /**
     * @deprecated Will be removed with next major release.
     */
    public const ACTION_COMPANY_ROLES_GET = 'get';

    public const RESPONSE_CODE_COMPANY_ROLE_NOT_FOUND = '2101';
    public const RESPONSE_DETAIL_COMPANY_ROLE_NOT_FOUND = 'Company role not found.';

    public const RESPONSE_CODE_COMPANY_ROLE_ID_IS_MISSING = '2102';
    public const RESPONSE_DETAIL_COMPANY_ROLE_ID_IS_MISSING = 'Company role id is missing.';

    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Resource is not implemented.';
    public const CURRENT_USER_COLLECTION_IDENTIFIER = 'mine';
}

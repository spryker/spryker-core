<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyRolesRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_COMPANY_ROLES = 'company-roles';

    /**
     * @var string
     */
    public const CONTROLLER_RESOURCE_COMPANY_ROLES = 'company-roles-resource';

    /**
     * @deprecated Will be removed with next major release.
     *
     * @var string
     */
    public const ACTION_COMPANY_ROLES_GET = 'get';

    /**
     * @var string
     */
    public const RESPONSE_CODE_COMPANY_ROLE_NOT_FOUND = '2101';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_COMPANY_ROLE_NOT_FOUND = 'Company role not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_COMPANY_USER_NOT_SELECTED = '2103';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_SELECTED = 'Current company user is not set. You need to select the current company user with /company-user-access-tokens in order to access the resource collection.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Resource is not implemented.';

    /**
     * @uses \Spryker\Glue\GlueApplication\GlueApplicationConfig::COLLECTION_IDENTIFIER_CURRENT_USER
     *
     * @var string
     */
    public const COLLECTION_IDENTIFIER_CURRENT_USER = 'mine';
}

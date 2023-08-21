<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AppCatalogGui;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AppCatalogGuiConstants
{
    /**
     * Base URL for app catalog page
     *
     * @api
     *
     * @var string
     */
    public const APP_CATALOG_SCRIPT_URL = 'APP_CATALOG_GUI:APP_CATALOG_SCRIPT_URL';

    /**
     * Specification:
     * - Provider name for Oauth.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_PROVIDER_NAME = 'APP_CATALOG_GUI:OAUTH_PROVIDER_NAME';

    /**
     * Specification:
     * - Grant type for Oauth.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_GRANT_TYPE = 'APP_CATALOG_GUI:OAUTH_GRANT_TYPE';

    /**
     * Specification:
     * - Audience option for Oauth.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_OPTION_AUDIENCE = 'APP_CATALOG_GUI:OAUTH_OPTION_AUDIENCE';

    /**
     * @var string
     */
    public const TENANT_IDENTIFIER = 'APP_CATALOG_GUI:TENANT_IDENTIFIER';
}

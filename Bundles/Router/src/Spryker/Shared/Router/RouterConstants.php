<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Router;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface RouterConstants
{
    /**
     * Specification:
     * - If option set to true, the application will create a router cache on the first request of a route.
     *
     * @api
     */
    public const IS_CACHE_ENABLED = 'ROUTER_ZED:IS_CACHE_ENABLED';

    /**
     * Specification:
     * - Path to where the cache files should be written to.
     *
     * @api
     */
    public const CACHE_PATH = 'ROUTER_ZED:CACHE_PATH';

    /**
     * Specification:
     * - If option set to true, the application will check if the request is secure and not excluded from https.
     * - If request is not secure and not excluded from https, the application will return a redirect response.
     * - If request is secure and page is excluded from https, the application will allow http.
     *
     * @api
     */
    public const IS_SSL_ENABLED = 'ROUTER_ZED:IS_SSL_ENABLED';

    /**
     * Specification:
     * - An array of HTTPS Excluded module/controller pairs when ssl is enabled.
     * - Example: `['module-a/controller-a', 'module-b/controller-b']`
     *
     * @api
     */
    public const SSL_EXCLUDED_ROUTE_NAMES = 'ROUTER_ZED:SSL_EXCLUDED_ROUTE_NAMES';
}

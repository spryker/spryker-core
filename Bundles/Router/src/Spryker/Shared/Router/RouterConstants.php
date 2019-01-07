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
    public const ROUTER_CACHE_ENABLED_YVES = 'ROUTER:ROUTER_CACHE_ENABLED_YVES';

    /**
     * Specification:
     * - If option set to true, the application will check if the request is secure and not excluded from https.
     * - If request is not secure and not excluded from https, the application will return a redirect response.
     * - If request is secure and page is excluded from https, the application will allow http.
     *
     * @api
     */
    public const ROUTER_IS_SSL_ENABLED_YVES = 'ROUTER:IS_SSL_ENABLED_YVES';

    /**
     * Specification:
     * - An array of HTTPS Excluded resources when ssl is enabled.
     * - Example: `['route-name-a' => '/url-a', 'route-name-b' => '/url-b']`
     *
     * @api
     */
    public const ROUTER_SSL_EXCLUDED_ROUTE_NAMES_YVES = 'ROUTER:SSL_EXCLUDED_ROUTE_NAMES_YVES';

    /**
     * Specification:
     * - If option set to true, the application will create a router cache on the first request of a route.
     *
     * @api
     */
    public const ROUTER_CACHE_ENABLED_ZED = 'ROUTER:ROUTER_CACHE_ENABLED_ZED';

    /**
     * Specification:
     * - If option set to true, the application will check if the request is secure and not excluded from https.
     * - If request is not secure and not excluded from https, the application will return a redirect response.
     * - If request is secure and page is excluded from https, the application will allow http.
     *
     * @api
     */
    public const ROUTER_IS_SSL_ENABLED_ZED = 'ROUTER:IS_SSL_ENABLED_ZED';

    /**
     * Specification:
     * - An array of HTTPS Excluded module/controller pairs when ssl is enabled.
     * - Example: `['module-a/controller-a', 'module-b/controller-b']`
     *
     * @api
     */
    public const ROUTER_SSL_EXCLUDED_ROUTE_NAMES_ZED = 'ROUTER:SSL_EXCLUDED_ROUTE_NAMES_ZED';
}

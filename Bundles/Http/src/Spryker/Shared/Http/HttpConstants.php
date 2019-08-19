<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Http;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface HttpConstants
{
    /**
     * Specification:
     * - If option set to true, the application will check if the request is secure and not excluded from https.
     * - If request is not secure and not excluded from https, the application will return a redirect response.
     * - If request is secure and page is excluded from https, the application will allow http.
     *
     * @api
     */
    public const YVES_SSL_ENABLED = 'HTTP:YVES_SSL_ENABLED';

    /**
     * Specification:
     * - An array of HTTPS Excluded resources when ssl is enabled.
     * - Example: `['route-name-a' => '/url-a', 'route-name-b' => '/url-b']`
     *
     * @api
     */
    public const YVES_SSL_EXCLUDED = 'HTTP:YVES_SSL_EXCLUDED';

    /**
     * Specification:
     * - IP address (or range) of your proxy.
     * - Example: `['192.0.0.1', '10.0.0.0/8']`.
     *
     * @api
     */
    public const YVES_TRUSTED_PROXIES = 'HTTP:YVES_TRUSTED_PROXIES';

    /**
     * Specification:
     * - A bit field of trusted Request::HEADER_*, to set which headers to trust from your proxies.
     *
     * @api
     */
    public const YVES_TRUSTED_HEADER = 'HTTP:YVES_TRUSTED_HEADER';

    /**
     * Specification:
     * - List of trusted hosts managed by regexp.
     *
     * @api
     */
    public const YVES_TRUSTED_HOSTS = 'HTTP:YVES_TRUSTED_HOSTS';

    /**
     * Specification:
     * - If option set to true, the application will set http strict transport header.
     *
     * @api
     */
    public const YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED = 'HTTP:YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED';

    /**
     * Specification:
     * - Http strict transport header body.
     *
     * @api
     */
    public const YVES_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG = 'HTTP:YVES_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG';

    /**
     * Specification:
     * - If option set to true, the application will check if the request is secure and not excluded from https.
     * - If request is not secure and not excluded from https, the application will return a redirect response.
     * - If request is secure and page is excluded from https, the application will allow http.
     *
     * @api
     */
    public const ZED_SSL_ENABLED = 'HTTP:ZED_SSL_ENABLED';

    /**
     * Specification:
     * - An array of HTTPS Excluded module/controller pairs when ssl is enabled.
     * - Example: `['module-a/controller-a', 'module-b/controller-b']`
     *
     * @api
     */
    public const ZED_SSL_EXCLUDED = 'HTTP:ZED_SSL_EXCLUDED';

    /**
     * Specification:
     * - IP address (or range) of your proxy.
     * - Example: `['192.0.0.1', '10.0.0.0/8']`.
     *
     * @api
     */
    public const ZED_TRUSTED_PROXIES = 'HTTP:ZED_TRUSTED_PROXIES';

    /**
     * Specification:
     * - List of trusted hosts managed by regexp.
     *
     * @api
     */
    public const ZED_TRUSTED_HOSTS = 'HTTP:ZED_TRUSTED_HOSTS';

    /**
     * Specification:
     * - If option set to true, the application will set http strict transport header.
     *
     * @api
     */
    public const ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED = 'HTTP:ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED';

    /**
     * Specification:
     * - Http strict transport header body.
     *
     * @api
     */
    public const ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG = 'HTTP:ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG';
}

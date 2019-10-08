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
     *  - HTTP port for Yves.
     *
     * @api
     */
    public const YVES_HTTP_PORT = 80;

    /**
     * Specification:
     * - HTTPS port for Yves.
     *
     * @api
     */
    public const YVES_HTTPS_PORT = 443;

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
     *  - HTTP port for Zed.
     *
     * @api
     */
    public const ZED_HTTP_PORT = 80;

    /**
     * Specification:
     * - HTTPS port for Zed.
     *
     * @api
     */
    public const ZED_HTTPS_PORT = 443;

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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Security;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SecurityConstants
{
    /**
     * Specification:
     *  - HTTP port for Yves.
     *
     * @api
     */
    public const YVES_HTTP_PORT = 'SECURITY:YVES_HTTP_PORT';

    /**
     * Specification:
     * - HTTPS port for Yves.
     *
     * @api
     */
    public const YVES_HTTPS_PORT = 'SECURITY:YVES_HTTPS_PORT';

    /**
     * Specification:
     *  - HTTP port for Zed.
     *
     * @api
     */
    public const ZED_HTTP_PORT = 'SECURITY:ZED_HTTP_PORT';

    /**
     * Specification:
     * - HTTPS port for Zed.
     *
     * @api
     */
    public const ZED_HTTPS_PORT = 'SECURITY:ZED_HTTPS_PORT';
}

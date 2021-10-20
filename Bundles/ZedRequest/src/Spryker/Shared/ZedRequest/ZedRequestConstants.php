<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ZedRequestConstants
{
    /**
     * @deprecated Will be removed without replacement.
     * @var string
     */
    public const AUTH_ZED_ENABLED = 'AUTH_ZED_ENABLED';

    /**
     * @var string
     */
    public const AUTH_DEFAULT_CREDENTIALS = 'AUTH_DEFAULT_CREDENTIALS';

    /**
     * @deprecated Use {@link ZedRequestConstants::BASE_URL_SSL_ZED_API} instead
     * @var string
     */
    public const HOST_SSL_ZED_API = 'HOST_SSL_ZED_API';

    /**
     * @var string
     */
    public const HOST_ZED_API = 'HOST_ZED_API';

    /**
     * Base URL for Zed API including scheme and port (e.g. http://www.zed.demoshop.local:8080)
     *
     * @api
     * @var string
     */
    public const BASE_URL_ZED_API = 'ZED_REQUEST:BASE_URL_ZED_API';

    /**
     * Secure base URL for Zed API including scheme and port (e.g. http://www.zed.demoshop.local:8443)
     *
     * @api
     * @var string
     */
    public const BASE_URL_SSL_ZED_API = 'ZED_REQUEST:BASE_URL_SSL_ZED_API';

    /**
     * @var string
     */
    public const TRANSFER_DEBUG_SESSION_FORWARD_ENABLED = 'TRANSFER_DEBUG_SESSION_FORWARD_ENABLED';

    /**
     * @var string
     */
    public const TRANSFER_DEBUG_SESSION_NAME = 'TRANSFER_DEBUG_SESSION_NAME';

    /**
     * @deprecated Will be removed without replacement.
     * @var string
     */
    public const TRANSFER_PASSWORD = 'TRANSFER_PASSWORD';

    /**
     * @deprecated Will be removed without replacement.
     * @var string
     */
    public const TRANSFER_USERNAME = 'TRANSFER_USERNAME';

    /**
     * @var string
     */
    public const ZED_API_SSL_ENABLED = 'ZED_API_SSL_ENABLED';

    /**
     * @var string
     */
    public const SET_REPEAT_DATA = 'SET_REPEAT_DATA';

    /**
     * @var string
     */
    public const YVES_REQUEST_REPEAT_DATA_PATH = 'YVES_REQUEST_REPEAT_DATA_PATH';

    /**
     * Specification:
     * - An array of settings to be used for the Client.
     *
     * @api
     * @var string
     */
    public const CLIENT_OPTIONS = 'ZED_REQUEST:CLIENT_OPTIONS';

    /**
     * Specification:
     * - Sets the permission mode for generated directories.
     *
     * @api
     * @var string
     */
    public const DIRECTORY_PERMISSION = 'ZED_REQUEST:DIRECTORY_PERMISSION';

    /**
     * Specification:
     * - Enables the mode when a request to Zed can be repeated with the same data for debugging/testing needs.
     *
     * @api
     * @var string
     */
    public const ENABLE_REPEAT = 'ZED_REQUEST:ENABLE_REPEAT';

    /**
     * Specification:
     * - Enables forwarding of Xdebug profiling.
     *
     * @api
     * @var string
     */
    public const XDEBUG_PROFILER_FORWARD_ENABLED = 'ZED_REQUEST:XDEBUG_PROFILER_FORWARD_ENABLED';

    /**
     * Specification:
     * - Xdebug profiler name.
     *
     * @api
     * @var string
     */
    public const XDEBUG_PROFILER_NAME = 'ZED_REQUEST:XDEBUG_PROFILER_NAME';
}

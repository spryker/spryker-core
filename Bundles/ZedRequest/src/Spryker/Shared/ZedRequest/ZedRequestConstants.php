<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest;

interface ZedRequestConstants
{

    const AUTH_ZED_ENABLED = 'AUTH_ZED_ENABLED';
    const AUTH_DEFAULT_CREDENTIALS = 'AUTH_DEFAULT_CREDENTIALS';

    /** @deprecated Please use ZedRequestConstants::BASE_URL_SSL_ZED_API instead */
    const HOST_SSL_ZED_API = 'HOST_SSL_ZED_API';

    const HOST_ZED_API = 'HOST_ZED_API';

    /**
     * Base URL for Zed API including scheme and port (e.g. http://www.zed.demoshop.local:8080)
     *
     * @api
     */
    const BASE_URL_ZED_API = 'ZED_REQUEST:BASE_URL_ZED_API';

    /**
     * Secure base URL for Zed API including scheme and port (e.g. http://www.zed.demoshop.local:8443)
     *
     * @api
     */
    const BASE_URL_SSL_ZED_API = 'ZED_REQUEST:BASE_URL_SSL_ZED_API';

    const TRANSFER_DEBUG_SESSION_FORWARD_ENABLED = 'TRANSFER_DEBUG_SESSION_FORWARD_ENABLED';
    const TRANSFER_DEBUG_SESSION_NAME = 'TRANSFER_DEBUG_SESSION_NAME';
    const TRANSFER_PASSWORD = 'TRANSFER_PASSWORD';
    const TRANSFER_USERNAME = 'TRANSFER_USERNAME';

    const ZED_API_SSL_ENABLED = 'ZED_API_SSL_ENABLED';

    const SET_REPEAT_DATA = 'SET_REPEAT_DATA';

    const YVES_REQUEST_REPEAT_DATA_PATH = 'YVES_REQUEST_REPEAT_DATA_PATH';

}

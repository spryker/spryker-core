<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\ZedRequest;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Auth\AuthConstants;

interface ZedRequestConstants
{

    const AUTH_DEFAULT_CREDENTIALS = AuthConstants::AUTH_DEFAULT_CREDENTIALS;

    const HOST_SSL_ZED_API = ApplicationConstants::HOST_SSL_ZED_API;
    const HOST_ZED_API = ApplicationConstants::HOST_ZED_API;

    const TRANSFER_DEBUG_SESSION_FORWARD_ENABLED = ApplicationConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED;
    const TRANSFER_DEBUG_SESSION_NAME = ApplicationConstants::TRANSFER_DEBUG_SESSION_NAME;
    const TRANSFER_PASSWORD = ApplicationConstants::TRANSFER_PASSWORD;
    const TRANSFER_USERNAME = ApplicationConstants::TRANSFER_USERNAME;

    const ZED_API_SSL_ENABLED = ApplicationConstants::ZED_API_SSL_ENABLED;
}

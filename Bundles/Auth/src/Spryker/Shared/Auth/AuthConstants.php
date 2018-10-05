<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Auth;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AuthConstants
{
    public const AUTH_DEFAULT_CREDENTIALS = 'AUTH_DEFAULT_CREDENTIALS';
    public const AUTH_ZED_ENABLED = 'AUTH_ZED_ENABLED';

    public const AUTH_SESSION_KEY = 'auth';
    public const AUTH_CURRENT_USER_KEY = '%s:currentUser:%s';
    public const AUTHORIZATION_WILDCARD = '*';
    public const DAY_IN_SECONDS = 86400;
    public const AUTH_TOKEN = 'Auth-Token';
}

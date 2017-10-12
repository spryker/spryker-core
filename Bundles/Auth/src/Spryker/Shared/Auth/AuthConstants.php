<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Auth;

interface AuthConstants
{
    const AUTH_DEFAULT_CREDENTIALS = 'AUTH_DEFAULT_CREDENTIALS';
    const AUTH_ZED_ENABLED = 'AUTH_ZED_ENABLED';

    const AUTH_SESSION_KEY = 'auth';
    const AUTH_CURRENT_USER_KEY = '%s:currentUser:%s';
    const AUTHORIZATION_WILDCARD = '*';
    const DAY_IN_SECONDS = 86400;
    const AUTH_TOKEN = 'Auth-Token';
}

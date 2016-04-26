<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Auth;

interface AuthConstants
{

    const AUTH_DEFAULT_CREDENTIALS = 'AUTH_DEFAULT_CREDENTIALS';
    const AUTH_STATIC_CREDENTIAL = 'AUTH_STATIC_CREDENTIAL';

    const AUTH_TYPE = 'AUTH_TYPE';

    const AUTH_SESSION_KEY = 'auth';
    const AUTH_CURRENT_USER_KEY = '%s:currentUser:%s';
    const AUTHORIZATION_WILDCARD = '*';
    const DAY_IN_SECONDS = 86400;
    const AUTH_TOKEN = 'Auth-Token';
    const AUTH_STATIC_USERNAME_HEADER = 'x-static-user';
    const AUTH_STATIC_PASSWORD_HEADER = 'x-static-pass';

    const AUTHENTICATE_NONE = 0;
    const AUTHENTICATE_STATIC = 1;
    const AUTHENTICATE_DYNAMIC = 2;


}

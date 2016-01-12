<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Auth;

interface AuthConstants
{

    const AUTH_DEFAULT_CREDENTIALS = 'AUTH_DEFAULT_CREDENTIALS';

    const AUTH_SESSION_KEY = 'auth';
    const AUTH_CURRENT_USER_KEY = '%s:currentUser:%s';
    const AUTHORIZATION_WILDCARD = '*';
    const DAY_IN_SECONDS = 86400;
    const AUTH_TOKEN = 'Auth-Token';

}

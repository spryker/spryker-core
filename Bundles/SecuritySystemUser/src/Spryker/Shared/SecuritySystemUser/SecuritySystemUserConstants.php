<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecuritySystemUser;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SecuritySystemUserConstants
{
    public const AUTH_DEFAULT_CREDENTIALS = 'AUTH_DEFAULT_CREDENTIALS';
    public const SYSTEM_USER_SESSION_REDIS_LIFE_TIME = 'AUTH:SYSTEM_USER_SESSION_REDIS_LIFE_TIME';
}

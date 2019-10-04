<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Auth\PageObject;

class LoginPage
{
    public const URL = '/auth/login';

    public const ADMIN_USERNAME = 'admin@spryker.com';
    public const ADMIN_PASSWORD = 'change123';

    public const SELECTOR_USERNAME_FIELD = '#auth_username';
    public const SELECTOR_PASSWORD_FIELD = '#auth_password';
    public const SELECTOR_SUBMIT_BUTTON = 'Login';

    public const AUTHENTICATION_FAILED = 'Authentication failed!';

    public const ERROR_MESSAGE_EMPTY_FIELD = 'This value should not be blank.';
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthCompanyUser;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface OauthCompanyUserConstants
{
    /**
     * Specification:
     *  - The identifier of OAUTH client to use when requesting for access tokens.
     *
     * @api
     */
    public const OAUTH_CLIENT_IDENTIFIER = 'OAUTH_COMPANY_USER:OAUTH_CLIENT_IDENTIFIER';

    /**
     * Specification:
     *  - The secret of OAUTH client to use when requesting for access tokens.
     *
     * @api
     */
    public const OAUTH_CLIENT_SECRET = 'OAUTH_COMPANY_USER:OAUTH_CLIENT_SECRET';
}

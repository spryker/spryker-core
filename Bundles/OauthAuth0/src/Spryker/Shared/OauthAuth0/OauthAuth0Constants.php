<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthAuth0;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface OauthAuth0Constants
{
    /**
     * Specification:
     *  - The identifier of OAuth client to use when requesting for access tokens.
     *
     * @api
     *
     * @var string
     */
    public const AUTH0_CLIENT_ID = 'AUTH_AUTH0:AUTH0_CLIENT_ID';

    /**
     * Specification:
     *  - The secret of OAuth client to use when requesting for access tokens.
     *
     * @api
     *
     * @var string
     */
    public const AUTH0_CLIENT_SECRET = 'AUTH_AUTH0:AUTH0_CLIENT_SECRET';

    /**
     * Specification:
     *  - The custom domain used for the Auth0 login.
     *
     * @api
     *
     * @var string
     */
    public const AUTH0_CUSTOM_DOMAIN = 'AUTH_AUTH0:AUTH0_CUSTOM_DOMAIN';
}

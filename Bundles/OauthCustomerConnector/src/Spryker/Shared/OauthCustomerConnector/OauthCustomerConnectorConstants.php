<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthCustomerConnector;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface OauthCustomerConnectorConstants
{
    /**
     * @deprecated Will be removed in the next major.
     *
     * Specification:
     *  - The identifier of OAUTH client to use when requesting for access tokens.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_CLIENT_IDENTIFIER = 'OAUTH_CLIENT_IDENTIFIER';

    /**
     * @deprecated Will be removed in the next major.
     *
     * Specification:
     *  - The secret of OAUTH client to use when requesting for access tokens.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_CLIENT_SECRET = 'OAUTH_CLIENT_SECRET';
}

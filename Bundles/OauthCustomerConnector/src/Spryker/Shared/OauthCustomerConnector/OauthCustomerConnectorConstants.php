<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthCustomerConnector;

interface OauthCustomerConnectorConstants
{
    /**
     * Specification:
     *  - The identifier of OAUTH client to use when requesting for access tokens.
     */
    public const OAUTH_CLIENT_IDENTIFIER = 'OAUTH_CLIENT_IDENTIFIER';

    /**
     * Specification:
     *  - The secret of OAUTH client to use when requesting for access tokens.
     */
    public const OAUTH_CLIENT_SECRET = 'OAUTH_CLIENT_SECRET';
}

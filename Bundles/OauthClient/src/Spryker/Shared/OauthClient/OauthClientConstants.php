<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthClient;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface OauthClientConstants
{
    /**
     * Specification:
     * - Oauth provider name for message broker.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_PROVIDER_NAME_FOR_MESSAGE_BROKER = 'OAUTH_CLIENT:OAUTH_PROVIDER_NAME_FOR_MESSAGE_BROKER';

    /**
     * Specification:
     * - Oauth grant type for message broker.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_GRANT_TYPE_FOR_MESSAGE_BROKER = 'OAUTH_CLIENT:OAUTH_GRANT_TYPE_FOR_MESSAGE_BROKER';

    /**
     * Specification:
     * - Oauth audience option for message broker.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_OPTION_AUDIENCE_FOR_MESSAGE_BROKER = 'OAUTH_CLIENT:OAUTH_OPTION_AUDIENCE_FOR_MESSAGE_BROKER';

    /**
     * Specification:
     * - Oauth provider name for payment authorization.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_PROVIDER_NAME_FOR_PAYMENT_AUTHORIZE = 'OAUTH_CLIENT:OAUTH_PROVIDER_NAME_FOR_PAYMENT_AUTHORIZE';

    /**
     * Specification:
     * - Oauth grant type for payment authorization.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_GRANT_TYPE_FOR_PAYMENT_AUTHORIZE = 'OAUTH_CLIENT:OAUTH_GRANT_TYPE_FOR_PAYMENT_AUTHORIZE';

    /**
     * Specification:
     * - Oauth audience option for payment authorization.
     *
     * @api
     *
     * @var string
     */
    public const OAUTH_OPTION_AUDIENCE_FOR_PAYMENT_AUTHORIZE = 'OAUTH_CLIENT:OAUTH_OPTION_AUDIENCE_FOR_PAYMENT_AUTHORIZE';

    /**
     * @var string
     */
    public const TENANT_IDENTIFIER = 'OAUTH_CLIENT:TENANT_IDENTIFIER';
}

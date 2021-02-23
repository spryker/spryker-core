<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\AuthRestApi\AuthRestApiConfig getSharedConfig()
 */
class AuthRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_TOKEN = 'token';


    public const RESOURCE_ACCESS_TOKENS = 'access-tokens';
    public const RESOURCE_REFRESH_TOKENS = 'refresh-tokens';

    public const CLIENT_GRANT_PASSWORD = 'password';
    public const CLIENT_GRANT_REFRESH_TOKEN = 'refresh_token';

    public const RESPONSE_DETAIL_MISSING_ACCESS_TOKEN = 'Missing access token.';
    public const RESPONSE_DETAIL_INVALID_ACCESS_TOKEN = 'Invalid access token.';
    public const RESPONSE_DETAIL_MESSAGE_ANONYMOUS_USER_WITH_ACCESS_TOKEN = 'Headers request error. A user can\'t act as logged and not logged user at the same time.';

    public const RESPONSE_CODE_ACCESS_CODE_INVALID = '001';
    public const RESPONSE_CODE_FORBIDDEN = '002';
    public const RESPONSE_INVALID_LOGIN = '003';
    public const RESPONSE_INVALID_REFRESH_TOKEN = '004';
    public const RESPONSE_CODE_ANONYMOUS_USER_WITH_ACCESS_TOKEN = '005';

    /**
     * @uses \Spryker\Glue\GlueApplication\GlueApplicationConfig::COLLECTION_IDENTIFIER_CURRENT_USER
     */
    public const COLLECTION_IDENTIFIER_CURRENT_USER = 'mine';

    /**
     * @uses \Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface::HEADER_AUTHORIZATION
     */
    public const HEADER_AUTHORIZATION = 'authorization';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID
     */
    public const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->getSharedConfig()->getClientSecret();
    }

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->getSharedConfig()->getClientId();
    }
}

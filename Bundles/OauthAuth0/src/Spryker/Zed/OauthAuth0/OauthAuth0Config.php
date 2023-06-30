<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAuth0;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\OauthAuth0\OauthAuth0Config getSharedConfig()
 */
class OauthAuth0Config extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const PROVIDER_NAME = 'auth0';

    /**
     * @var string
     */
    public const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';

    /**
     * @var string
     */
    public const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';

    /**
     * @var string
     */
    public const GRANT_TYPE_PASSWORD = 'password';

    /**
     * @var string
     */
    public const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return static::PROVIDER_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->getSharedConfig()->getClientId();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->getSharedConfig()->getClientSecret();
    }
}

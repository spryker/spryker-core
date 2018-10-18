<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector;

use Spryker\Shared\OauthCustomerConnector\OauthCustomerConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthCustomerConnectorConfig extends AbstractBundleConfig
{
    /**
     * The client secret used to authenticate Oauth client requests, to create use "password_hash('your password', PASSWORD_BCRYPT)".
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->get(OauthCustomerConnectorConstants::OAUTH_CLIENT_SECRET);
    }

    /**
     * The client id as is store in spy_oauth_client database table
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->get(OauthCustomerConnectorConstants::OAUTH_CLIENT_IDENTIFIER);
    }

    /**
     * @return array
     */
    public function getCustomerScopes(): array
    {
        return ['customer'];
    }
}

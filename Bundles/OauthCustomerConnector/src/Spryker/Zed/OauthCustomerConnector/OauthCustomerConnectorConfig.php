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
    public const SCOPE_CUSTOMER = 'customer';
    protected const SCOPE_CUSTOMER_IMPERSONATION = 'customer_impersonation';

    /**
     * @uses \Spryker\Zed\Oauth\OauthConfig::GRANT_TYPE_PASSWORD
     */
    public const GRANT_TYPE_PASSWORD = 'password';

    public const GRANT_TYPE_CUSTOMER_IMPERSONATION = 'customer_impersonation';

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * The client secret used to authenticate Oauth client requests, to create use "password_hash('your password', PASSWORD_BCRYPT)".
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->get(OauthCustomerConnectorConstants::OAUTH_CLIENT_SECRET);
    }

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * The client id as is store in spy_oauth_client database table
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->get(OauthCustomerConnectorConstants::OAUTH_CLIENT_IDENTIFIER);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getCustomerScopes(): array
    {
        return [static::SCOPE_CUSTOMER];
    }

    /**
     * Specification:
     * - Returns customer impersonation scopes.
     *
     * @api
     *
     * @return string[]
     */
    public function getCustomerImpersonationScopes(): array
    {
        return [
            static::SCOPE_CUSTOMER_IMPERSONATION,
            static::SCOPE_CUSTOMER,
        ];
    }
}

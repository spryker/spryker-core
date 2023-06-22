<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient;

use Spryker\Shared\OauthClient\OauthClientConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthClientConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isAccessTokenRequestExpandedByMessageAttributes(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthProviderNameForMessageBroker(): string
    {
        return $this->get(OauthClientConstants::OAUTH_PROVIDER_NAME_FOR_MESSAGE_BROKER, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthGrantTypeForMessageBroker(): string
    {
        return $this->get(OauthClientConstants::OAUTH_GRANT_TYPE_FOR_MESSAGE_BROKER, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthOptionAudienceForMessageBroker(): string
    {
        return $this->get(OauthClientConstants::OAUTH_OPTION_AUDIENCE_FOR_MESSAGE_BROKER, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthProviderNameForPaymentAuthorize(): string
    {
        return $this->get(OauthClientConstants::OAUTH_PROVIDER_NAME_FOR_PAYMENT_AUTHORIZE, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthGrantTypeForPaymentAuthorize(): string
    {
        return $this->get(OauthClientConstants::OAUTH_GRANT_TYPE_FOR_PAYMENT_AUTHORIZE, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthOptionAudienceForPaymentAuthorize(): string
    {
        return $this->get(OauthClientConstants::OAUTH_OPTION_AUDIENCE_FOR_PAYMENT_AUTHORIZE, '');
    }
}

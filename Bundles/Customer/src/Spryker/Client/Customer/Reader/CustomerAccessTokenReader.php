<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Reader;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Client\CustomerExtension\Dependency\Plugin\AccessTokenAuthenticationHandlerPluginInterface;

class CustomerAccessTokenReader implements CustomerAccessTokenReaderInterface
{
    /**
     * @var \Spryker\Client\CustomerExtension\Dependency\Plugin\AccessTokenAuthenticationHandlerPluginInterface
     */
    protected $accessTokenAuthenticationHandlerPlugin;

    /**
     * @param \Spryker\Client\CustomerExtension\Dependency\Plugin\AccessTokenAuthenticationHandlerPluginInterface $accessTokenAuthenticationHandlerPlugin
     */
    public function __construct(AccessTokenAuthenticationHandlerPluginInterface $accessTokenAuthenticationHandlerPlugin)
    {
        $this->accessTokenAuthenticationHandlerPlugin = $accessTokenAuthenticationHandlerPlugin;
    }

    /**
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessToken(string $accessToken): CustomerResponseTransfer
    {
        return $this->accessTokenAuthenticationHandlerPlugin->getCustomerByAccessToken($accessToken);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Communication\Plugin\KernelApp;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\OauthClient\Business\OauthClientFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthClient\OauthClientConfig getConfig()
 */
class OAuthRequestExpanderPlugin extends AbstractPlugin implements RequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves an access token from an access token provider by AccessTokenRequestTransfer.
     * - Throws exception `AccessTokenNotFoundException` in case if `AccessTokenResponseTransfer::isSuccessful = false`.
     * - Adds the authorization header to the `AcpHttpRequestTransfer`.
     * - Returns the `AcpHttpRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    public function expandRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
    {
        return $this->getFacade()->expandRequest($acpHttpRequestTransfer);
    }
}

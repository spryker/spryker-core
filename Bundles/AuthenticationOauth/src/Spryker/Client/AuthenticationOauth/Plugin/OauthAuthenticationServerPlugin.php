<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthenticationOauth\Plugin;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface;

/**
 * @method \Spryker\Client\AuthenticationOauth\AuthenticationOauthClientInterface getClient()
 */
class OauthAuthenticationServerPlugin extends AbstractPlugin implements AuthenticationServerPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_STOREFRONT_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * {@inheritDoc}
     *  - Checks whether the requested application context equals to GlueStorefrontApiApplication.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): bool
    {
        return $glueAuthenticationRequestTransfer->getRequestContextOrFail()->getRequestApplication() === static::GLUE_STOREFRONT_API_APPLICATION;
    }

    /**
     * {@inheritDoc}
     * - Makes request to proccess access token.
     * - Sets `OauthResponseTransfer` to `GlueAuthenticationResponseTransfer`.
     *
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function authenticate(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer
    {
         return $this->getClient()->authenticate($glueAuthenticationRequestTransfer);
    }
}

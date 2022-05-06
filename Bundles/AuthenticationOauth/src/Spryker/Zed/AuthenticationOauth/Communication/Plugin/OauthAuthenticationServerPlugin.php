<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationOauth\Communication\Plugin;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AuthenticationOauth\Business\AuthenticationOauthFacadeInterface getFacade()
 * @method \Spryker\Zed\AuthenticationOauth\AuthenticationOauthConfig getConfig()
 */
class OauthAuthenticationServerPlugin extends AbstractPlugin implements AuthenticationServerPluginInterface
{
    /**
     * @see \Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     *  - Checks whether the requested application context equals to GlueBackendApiApplication.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): bool
    {
        return $glueAuthenticationRequestTransfer->getRequestContextOrFail()->getRequestApplication() === static::GLUE_BACKEND_API_APPLICATION;
    }

    /**
     * {@inheritDoc}
     * - Makes request to proccess access token.
     * - Builds `GlueAuthenticationResponseTransfer` with proper access token if the credentials are valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function authenticate(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer
    {
         return $this->getFacade()->authenticate($glueAuthenticationRequestTransfer);
    }
}

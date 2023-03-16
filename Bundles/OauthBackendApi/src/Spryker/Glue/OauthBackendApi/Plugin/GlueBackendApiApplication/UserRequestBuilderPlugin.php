<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\OauthBackendApi\OauthBackendApiFactory getFactory()
 */
class UserRequestBuilderPlugin extends AbstractPlugin implements RequestBuilderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds access token data using `GlueRequest`.
     * - Extracts user identifier data from found access token data.
     * - Maps user identifier user id to `GlueRequest.requestUser.surrogateIdentifier`.
     * - Maps user identifier user uuid to `GlueRequest.requestUser.naturalIdentifier`.
     * - Returns empty `GlueRequest.requestUser` when access token data or user identifier are not exist or invalid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        return $this->getFactory()->createRequestBuilder()->buildUserRequest($glueRequestTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\OauthBackendApi\OauthBackendApiFactory getFactory()
 */
class AccessTokenValidatorPlugin extends AbstractPlugin implements RequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates access token passed via Authorization header.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        return $this->getFactory()
            ->createAccessTokenValidator()
            ->validate($glueRequestTransfer);
    }
}

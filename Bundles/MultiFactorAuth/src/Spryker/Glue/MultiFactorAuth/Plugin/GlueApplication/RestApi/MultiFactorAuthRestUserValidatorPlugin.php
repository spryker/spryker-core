<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\Plugin\GlueApplication\RestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 */
class MultiFactorAuthRestUserValidatorPlugin extends AbstractPlugin implements RestUserValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates that the X-MFA-Code header is present for Multi-Factor-Auth-protected resources.
     * - Checks if the requested resource is in the list of Multi-Factor-Auth-protected resources.
     * - Validates the Multi-Factor Auth code using the MultiFactorAuthClient.
     * - Returns error message if Multi-Factor Auth header is missing or code is invalid for protected resource.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        return $this->getFactory()
            ->createMultiFactorAuthRestUserValidator()
            ->validate($restRequest);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MultiFactorAuthBackendApiRequestValidatorPlugin extends AbstractPlugin implements RequestAfterRoutingValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates that the X-MFA-Code header is present for MFA-protected resources.
     * - Checks if the requested resource is in the list of MFA-protected resources.
     * - Validates the MFA code using the MultiFactorAuthFacade.
     * - Returns error message if MFA header is missing or code is invalid for protected resource.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer, ResourceInterface $resource): GlueRequestValidationTransfer
    {
        return $this->getFactory()
            ->createMultiFactorAuthBackendApiRequestValidator()
            ->validate($glueRequestTransfer, $resource);
    }
}

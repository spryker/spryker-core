<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 */
class RequestCorsValidatorPlugin extends AbstractPlugin implements RequestAfterRoutingValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates that the `access-control-request-method` header is present and allowed for the resource.
     * - Validates that the `access-control-request-headers` header is present and is allowed in the `\Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig::getCorsAllowedHeaders()`.
     * - Does nothing if the method used by the request is not OPTIONS.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validateRequest(GlueRequestTransfer $glueRequestTransfer, ResourceInterface $resource): GlueRequestValidationTransfer
    {
        return $this->getFactory()->createRequestCorsValidator()->validate(
            $glueRequestTransfer,
            $resource,
        );
    }
}

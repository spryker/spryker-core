<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueHttp\GlueHttpFactory getFactory()
 */
class CorsHeaderExistenceRequestAfterRoutingValidatorPlugin extends AbstractPlugin implements RequestAfterRoutingValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates that all the required headers are passed with the OPTIONS request.
     * - Validates that the `access-control-request-method` header is present.
     * - Validates that the `access-control-request-headers` header is present.
     * - Validates that the `origin` header is present.
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
        return $this->getFactory()->createCorsHeaderExistenceValidator()->validate($glueRequestTransfer);
    }
}

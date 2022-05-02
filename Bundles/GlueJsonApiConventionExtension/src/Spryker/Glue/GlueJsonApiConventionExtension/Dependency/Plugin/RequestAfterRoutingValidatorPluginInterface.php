<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;

/**
 * Use this plugin for implementing validations that need to be aware of the route.
 * Validation must be specific to the JSON:API convention.
 */
interface RequestAfterRoutingValidatorPluginInterface
{
    /**
     * Specification:
     * - Execute validations that need to be aware of the resolved route.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validateRequest(GlueRequestTransfer $glueRequestTransfer, JsonApiResourceInterface $resource): GlueRequestValidationTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;

/**
 * Validates user from the backend API request.
 *
 * Use this plugin if some backend API requests need to be approved\declined depending on request information.
 * This plugin is executed only once before resource processing.
 */
interface UserRequestValidationPreCheckerPluginInterface
{
    /**
     * Specification:
     * - Updates `GlueRequestTransfer.isValid` depending on the request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function preCheck(
        GlueRequestTransfer $glueRequestTransfer,
        GlueRequestValidationTransfer $glueRequestValidationTransfer
    ): GlueRequestValidationTransfer;
}

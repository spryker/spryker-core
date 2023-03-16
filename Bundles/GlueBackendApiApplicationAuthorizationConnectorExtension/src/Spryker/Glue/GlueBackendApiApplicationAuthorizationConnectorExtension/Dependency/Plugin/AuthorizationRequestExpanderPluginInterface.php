<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;

/**
 * Expands authorization request before authorize call.
 */
interface AuthorizationRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `AuthorizationRequestTransfer` with additional data.
     * - Uses `GlueRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function expand(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): AuthorizationRequestTransfer;
}

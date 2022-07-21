<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AuthenticationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;

/**
 * This plugin is used to provide the access token.
 */
interface AuthenticationServerPluginInterface
{
    /**
     * Specification:
     * - Checks whether the requested application scope is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): bool;

    /**
     * Specification:
     * - Builds request to authentication server.
     * - Process token request.
     * - Gets `GlueAuthenticationResponseTransfer` with proper access token if user credentials are valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function authenticate(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer;
}

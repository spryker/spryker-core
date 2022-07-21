<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

/**
 * This plugin is used to build the proper grant type configuration.
 */
interface OauthRequestGrantTypeConfigurationProviderPluginInterface
{
    /**
     * Specification:
     * - Checks whether the requested grant type and application scope are applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer $glueAuthenticationRequestContextTransfer
     *
     * @return bool
     */
    public function isApplicable(
        OauthRequestTransfer $oauthRequestTransfer,
        GlueAuthenticationRequestContextTransfer $glueAuthenticationRequestContextTransfer
    ): bool;

    /**
     * Specification:
     * - Returns grant type configuration.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer
     */
    public function getGrantTypeConfiguration(): OauthGrantTypeConfigurationTransfer;
}

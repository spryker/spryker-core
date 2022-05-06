<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface} instead.
 */
interface OauthGrantTypeConfigurationProviderPluginInterface
{
    /**
     * Specification:
     *  - Returns grant type configuration.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer
     */
    public function getGrantTypeConfiguration(): OauthGrantTypeConfigurationTransfer;
}

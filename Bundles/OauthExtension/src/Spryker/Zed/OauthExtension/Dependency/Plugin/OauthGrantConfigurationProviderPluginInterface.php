<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthGrantConfigurationTransfer;

interface OauthGrantConfigurationProviderPluginInterface
{
    /**
     * Specification:
     *  - Returns grant configuration.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantConfigurationTransfer
     */
    public function getGrantConfiguration(): OauthGrantConfigurationTransfer;
}

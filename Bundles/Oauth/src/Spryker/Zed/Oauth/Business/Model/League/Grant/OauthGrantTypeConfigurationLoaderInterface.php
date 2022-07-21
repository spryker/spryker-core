<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

interface OauthGrantTypeConfigurationLoaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer $glueAuthenticationRequestContextTransfer
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer|null
     */
    public function loadGrantTypeConfiguration(
        OauthRequestTransfer $oauthRequestTransfer,
        GlueAuthenticationRequestContextTransfer $glueAuthenticationRequestContextTransfer
    ): ?OauthGrantTypeConfigurationTransfer;
}

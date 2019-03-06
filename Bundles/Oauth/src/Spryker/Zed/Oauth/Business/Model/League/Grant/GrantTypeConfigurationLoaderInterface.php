<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

interface GrantTypeConfigurationLoaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer|null
     */
    public function loadGrantTypeConfigurationByGrantType(OauthRequestTransfer $oauthRequestTransfer): ?OauthGrantTypeConfigurationTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use Generated\Shared\Transfer\OauthGrantConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

interface GrantConfigurationLoaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthGrantConfigurationTransfer|null
     */
    public function loadGrantConfigurationByGrantType(OauthRequestTransfer $oauthRequestTransfer): ?OauthGrantConfigurationTransfer;
}

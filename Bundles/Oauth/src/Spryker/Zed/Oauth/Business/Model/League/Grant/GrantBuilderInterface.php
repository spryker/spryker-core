<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use Generated\Shared\Transfer\OauthGrantConfigurationTransfer;

interface GrantBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthGrantConfigurationTransfer $oauthGrantConfigurationTransfer
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface
     */
    public function buildGrant(OauthGrantConfigurationTransfer $oauthGrantConfigurationTransfer): GrantInterface;
}

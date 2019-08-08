<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;

interface GrantBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer $oauthGrantTypeConfigurationTransfer
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface
     */
    public function buildGrant(OauthGrantTypeConfigurationTransfer $oauthGrantTypeConfigurationTransfer): GrantTypeInterface;
}

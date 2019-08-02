<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi\Dependency\Facade;

interface AuthRestApiToOauthFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(\Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer): \Generated\Shared\Transfer\OauthResponseTransfer;
}

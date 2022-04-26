<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreReference\Business\Expander;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;

interface StoreReferenceAccessTokenRequestExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui\Business\AccessToken;

use Generated\Shared\Transfer\AccessTokenResponseTransfer;

interface AccessTokenReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function requestAccessToken(): AccessTokenResponseTransfer;
}

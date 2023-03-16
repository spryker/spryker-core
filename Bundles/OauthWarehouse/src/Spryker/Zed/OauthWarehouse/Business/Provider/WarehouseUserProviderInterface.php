<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business\Provider;

use Generated\Shared\Transfer\OauthUserTransfer;

interface WarehouseUserProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthWarehouseUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;
}

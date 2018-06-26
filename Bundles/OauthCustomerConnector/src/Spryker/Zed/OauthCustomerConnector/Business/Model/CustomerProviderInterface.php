<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business\Model;

use Generated\Shared\Transfer\OauthUserTransfer;

interface CustomerProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getCustomer(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;
}

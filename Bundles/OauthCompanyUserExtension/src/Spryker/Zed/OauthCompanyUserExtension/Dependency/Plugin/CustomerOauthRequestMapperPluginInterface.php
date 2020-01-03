<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

interface CustomerOauthRequestMapperPluginInterface
{
    /**
     * Specification:
     * - Maps CustomerTransfer data to OauthRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    public function map(OauthRequestTransfer $oauthRequestTransfer, CustomerTransfer $customerTransfer): OauthRequestTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\Request;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;

interface RequestInterface
{
    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    public function addMerchantReferenceHeader(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer;
}

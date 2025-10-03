<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Expander;

use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantExpanderInterface
{
    public function expandMerchantTransferWithUrls(MerchantTransfer $merchantTransfer): MerchantTransfer;
}

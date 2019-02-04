<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model\Status;

use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantStatusValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function validateTransitionToStatus(MerchantTransfer $merchantTransfer): void;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

interface MerchantReturnValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validate(
        ReturnCreateRequestTransfer $returnCreateRequestTransfer,
        ArrayObject $itemTransfers
    ): ReturnResponseTransfer;
}

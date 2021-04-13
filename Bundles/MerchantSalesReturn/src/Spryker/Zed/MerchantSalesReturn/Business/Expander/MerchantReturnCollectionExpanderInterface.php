<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Expander;

use Generated\Shared\Transfer\ReturnCollectionTransfer;

interface MerchantReturnCollectionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function expand(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Updater;

use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductAbstractResponseTransfer;

interface ProductAbstractUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractResponseTransfer
     */
    public function updateProductAbstract(MerchantProductTransfer $merchantProductTransfer): ProductAbstractResponseTransfer;
}

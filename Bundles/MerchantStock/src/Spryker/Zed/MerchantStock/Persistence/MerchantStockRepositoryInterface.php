<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface MerchantStockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getMerchantStocksByMerchant(MerchantTransfer $merchantTransfer): ObjectCollection;
}

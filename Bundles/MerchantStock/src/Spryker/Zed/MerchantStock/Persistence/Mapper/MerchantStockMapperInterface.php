<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface MerchantStockMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $merchantStocksData
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function mapMerchantStocksDataToMerchantTransfer(
        ObjectCollection $merchantStocksData,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer;
}

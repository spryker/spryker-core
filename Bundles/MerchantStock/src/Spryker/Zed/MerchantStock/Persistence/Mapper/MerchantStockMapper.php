<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantStockMapper implements MerchantStockMapperInterface
{
    /**
     * @inheritDoc
     */
    public function mapMerchantStocksDataToMerchantTransfer(
        ObjectCollection $merchantStocksData,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        $stockCollection = new ArrayObject();

        foreach ($merchantStocksData as $merchantStockEntity) {
            $stockCollection->append(
                (new StockTransfer())
                ->setIdStock($merchantStockEntity->getFkStock())
                ->setName($merchantStockEntity->getStockName())
            );
        }

        return $merchantTransfer->setStockCollection($stockCollection);
    }
}

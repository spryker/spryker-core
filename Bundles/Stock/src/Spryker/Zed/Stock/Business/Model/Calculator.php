<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;

class Calculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\Stock\Business\Model\ReaderInterface
     */
    protected $reader;

    /**
     * @param \Spryker\Zed\Stock\Business\Model\ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param string $sku
     *
     * @return float
     */
    public function calculateStockForProduct($sku)
    {
        return $this->reader->getProductStockSumBySku($sku);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return float
     */
    public function calculateProductStockForStore($sku, StoreTransfer $storeTransfer)
    {
        return $this->reader->getProductStockSumBySkuAndStore($sku, $storeTransfer);
    }
}

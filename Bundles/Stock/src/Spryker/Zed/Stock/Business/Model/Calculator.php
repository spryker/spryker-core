<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

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
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        $productEntities = $this->reader->getStocksProduct($sku);
        $quantity = 0;

        foreach ($productEntities as $productEntity) {
            $quantity += $productEntity->getQuantity();
        }

        return $quantity;
    }
}

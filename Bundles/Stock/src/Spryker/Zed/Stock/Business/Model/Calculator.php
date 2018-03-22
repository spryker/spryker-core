<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Traversable;

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
        return $this->calculateTotalQuantity($productEntities);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateProductStockForStore($sku, StoreTransfer $storeTransfer)
    {
        $productEntities = $this->reader->findProductStocksForStore($sku, $storeTransfer);

        return $this->calculateTotalQuantity($productEntities);
    }

    /**
     * @param \Traversable|\Orm\Zed\Stock\Persistence\SpyStockProduct[] $productEntities
     *
     * @return int
     */
    protected function calculateTotalQuantity(Traversable $productEntities)
    {
        $quantity = 0;

        foreach ($productEntities as $productEntity) {
            $quantity += $productEntity->getQuantity();
        }

        return $quantity;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

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
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateStockForProduct(string $sku): Decimal
    {
        $productEntities = $this->reader->getStocksProduct($sku);

        return $this->calculateTotalQuantity($productEntities);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductStockForStore(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        $productEntities = $this->reader->findProductStocksForStore($sku, $storeTransfer);

        return $this->calculateTotalQuantity($productEntities);
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockProduct[] $productEntities
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateTotalQuantity(array $productEntities): Decimal
    {
        $quantity = new Decimal(0);
        foreach ($productEntities as $productEntity) {
            $quantity = $quantity->add($productEntity->getQuantity());
        }

        return $quantity;
    }
}

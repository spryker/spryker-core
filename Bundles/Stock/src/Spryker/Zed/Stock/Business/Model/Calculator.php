<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Stock\Business\StockProduct\StockProductReaderInterface;

class Calculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\Stock\Business\StockProduct\StockProductReaderInterface
     */
    protected $stockProductReader;

    /**
     * @param \Spryker\Zed\Stock\Business\StockProduct\StockProductReaderInterface $stockProductReader
     */
    public function __construct(StockProductReaderInterface $stockProductReader)
    {
        $this->stockProductReader = $stockProductReader;
    }

    /**
     * @param string $sku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateStockForProduct(string $sku): Decimal
    {
        $stockProductTransfers = $this->stockProductReader->getStocksProduct($sku);

        return $this->calculateTotalQuantity($stockProductTransfers);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductStockForStore(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        $stockProductTransfers = $this->stockProductReader->findProductStocksForStore($sku, $storeTransfer);

        return $this->calculateTotalQuantity($stockProductTransfers);
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductAbstractStockForStore(string $abstractSku, StoreTransfer $storeTransfer): Decimal
    {
        $stockProductTransfers = $this->stockProductReader->getStockProductByProductAbstractSkuForStore($abstractSku, $storeTransfer);

        return $this->calculateTotalQuantity($stockProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer[] $stockProductTransfers
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateTotalQuantity(array $stockProductTransfers): Decimal
    {
        $quantity = new Decimal(0);
        foreach ($stockProductTransfers as $stockProductTransfer) {
            $quantity = $quantity->add($stockProductTransfer->getQuantity());
        }

        return $quantity;
    }
}

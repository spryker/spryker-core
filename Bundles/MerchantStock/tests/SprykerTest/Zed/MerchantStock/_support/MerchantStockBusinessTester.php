<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStock;

use Codeception\Actor;
use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\MerchantStock\PHPMD)
 */
class MerchantStockBusinessTester extends Actor
{
    use _generated\MerchantStockBusinessTesterActions;

    /**
     * @param int $stocksCount
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchantWithStocks(int $stocksCount = 1): MerchantTransfer
    {
        $merchantTransfer = $this->haveMerchant();

        for ($i = 0; $i < $stocksCount; $i++) {
            $stockTransfer = $this->haveStock();
            $this->haveMerchantStock([
                MerchantStockTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
                MerchantStockTransfer::ID_STOCK => $stockTransfer->getIdStock(),
            ]);

            $merchantTransfer->addStock($stockTransfer);
        }

        return $merchantTransfer;
    }

    /**
     * @param int $expectedStockCount
     * @param \Generated\Shared\Transfer\MerchantTransfer $actualMerchantTransfer
     *
     * @return void
     */
    public function assertMerchantHasStocksCount(int $expectedStockCount, MerchantTransfer $actualMerchantTransfer): void
    {
        $stocks = $actualMerchantTransfer->getStocks()->getArrayCopy();

        $this->assertCount($expectedStockCount, $stocks);

        if ($expectedStockCount > 0) {
            $this->assertContainsOnlyInstancesOf(StockTransfer::class, $stocks);
        }
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantStock
 * @group Business
 * @group Facade
 * @group MerchantStockFacadeTest
 * Add your own group annotations below this line
 */
class MerchantStockFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantStock\MerchantStockBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateDefaultMerchantStockSuccessful(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        // Act
        $merchantTransfer = $this->tester->getFacade()
            ->createDefaultMerchantStock($merchantTransfer)
            ->getMerchant();

        // Assert
        $this->assertNotEmpty($merchantTransfer->getStocks());
        $this->assertInstanceOf(StockTransfer::class, $merchantTransfer->getStocks()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testGetReturnsRelatedStocks(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveMerchantStock([
            MerchantStockTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantStockTransfer::ID_STOCK => $stockTransfer->getIdStock(),
        ]);

        $merchantStockCriteriaTransfer = (new MerchantStockCriteriaTransfer())
            ->setIdMerchant($merchantTransfer->getIdMerchant());

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->get($merchantStockCriteriaTransfer);

        // Assert
        $this->assertNotEmpty($stockCollectionTransfer->getStocks());
        $this->assertSame($stockTransfer->getIdStock(), $stockCollectionTransfer->getStocks()->getIterator()->current()->getIdStock());
    }

    /**
     * @return void
     */
    public function testGetReturnsEmptyStocks(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $merchantStockCriteriaTransfer = (new MerchantStockCriteriaTransfer())
            ->setIdMerchant($merchantTransfer->getIdMerchant());

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->get($merchantStockCriteriaTransfer);

        // Assert
        $this->assertEmpty($stockCollectionTransfer->getStocks());
    }

    /**
     * @return void
     */
    public function testExpandMerchantCollectionWithStocksReturnsMerchantCollectionWithRelatedStocksIfExist(): void
    {
        // Arrange
        $merchantCollectionTransfer = new MerchantCollectionTransfer();
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchantWithStocks(2),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchant(),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchantWithStocks(),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchant(),
        );

        $this->tester->haveMerchant();

        // Act
        $resultMerchantCollectionTransfer = $this->tester->getFacade()
            ->expandMerchantCollectionWithStocks($merchantCollectionTransfer);

        // Assert
        $this->assertCount(4, $resultMerchantCollectionTransfer->getMerchants());
        $this->tester->assertMerchantHasStocksCount(
            2,
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(0),
        );
        $this->tester->assertMerchantHasStocksCount(
            0,
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(1),
        );
        $this->tester->assertMerchantHasStocksCount(
            1,
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(2),
        );
        $this->tester->assertMerchantHasStocksCount(
            0,
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(3),
        );
    }

    /**
     * @return void
     */
    public function testExpandMerchantCollectionWithStocksReturnsEmptyMerchantCollectionIfEmptyMerchantCollectionWasPassed(): void
    {
        // Arrange
        $merchantCollectionTransfer = new MerchantCollectionTransfer();

        // Act
        $resultMerchantCollectionTransfer = $this->tester->getFacade()
            ->expandMerchantCollectionWithStocks($merchantCollectionTransfer);

        // Assert
        $this->assertCount(0, $resultMerchantCollectionTransfer->getMerchants());
    }

    /**
     * @return void
     */
    public function testExpandMerchantCollectionWithStocksThrowsExceptionIfMerchantCollectionMerchantHasNoIdMerchant(): void
    {
        // Arrange
        $merchantCollectionTransfer = (new MerchantCollectionTransfer())
            ->addMerchants(new MerchantTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()
            ->expandMerchantCollectionWithStocks($merchantCollectionTransfer);
    }
}

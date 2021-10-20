<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductStorage\Business\Storage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriter;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriterInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeBridge;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeBridge;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface;
use Spryker\Zed\Store\Business\StoreFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductStorage
 * @group Business
 * @group Storage
 * @group PriceProductAbstractStorageWriterTest
 * Add your own group annotations below this line
 */
class PriceProductAbstractStorageWriterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductStorage\PriceProductStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPublishTwoAbstractProductsOnlyOneWithPrice(): void
    {
        // Prepare
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $this->tester->havePriceProduct([
            PriceProductTransfer::ID_PRICE_PRODUCT => $productAbstractTransfer->getIdProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstractTransfer->getSku(),
        ]);

        // Action
        $this->createPriceProductAbstractStorageWriter()->publish([
            $productAbstractTransfer->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        $this->assertCount(
            1,
            $this->createPriceProductStorageQueryContainer()
                ->queryPriceAbstractStorageByPriceAbstractIds([
                    $productAbstractTransfer->getIdProductAbstract(),
                    $productAbstractTransfer2->getIdProductAbstract(),
                ])->find(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriterInterface
     */
    protected function createPriceProductAbstractStorageWriter(): PriceProductAbstractStorageWriterInterface
    {
        return new PriceProductAbstractStorageWriter(
            new PriceProductStorageToPriceProductFacadeBridge(new PriceProductFacade()),
            new PriceProductStorageToStoreFacadeBridge(new StoreFacade()),
            new PriceProductStorageQueryContainer(),
            false,
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface
     */
    protected function createPriceProductStorageQueryContainer(): PriceProductStorageQueryContainerInterface
    {
        return new PriceProductStorageQueryContainer();
    }
}

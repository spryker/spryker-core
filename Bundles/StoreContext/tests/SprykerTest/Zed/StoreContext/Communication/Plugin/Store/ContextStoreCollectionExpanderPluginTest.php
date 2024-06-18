<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContext\Communication\Plugin\Store;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreContext\Business\StoreContextFacade;
use Spryker\Zed\StoreContext\Communication\Plugin\Store\ContextStoreCollectionExpanderPlugin;
use SprykerTest\Zed\StoreContext\StoreContextCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreContext
 * @group Communication
 * @group Plugin
 * @group Store
 * @group ContextStoreCollectionExpanderPluginTest
 * Add your own group annotations below this line
 */
class ContextStoreCollectionExpanderPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const FIRST_STORE_ID = 100;

    /**
     * @return int
     *
     * @var int
     */
    protected const SECOND_STORE_ID = 200;

    /**
     * @var \SprykerTest\Zed\StoreContext\StoreContextCommunicationTester
     */
    protected StoreContextCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandReturnsExpandedStoreTransfer(): void
    {
        // Arrange
        $plugin = new ContextStoreCollectionExpanderPlugin();

        $storeTransfers = [
            (new StoreTransfer())->setIdStore(static::FIRST_STORE_ID),
            (new StoreTransfer())->setIdStore(static::SECOND_STORE_ID),
        ];
        $mockFacade = $this->createPartialMock(StoreContextFacade::class, ['expandStoreCollection']);
        $mockFacade->method('expandStoreCollection')
            ->with(
                $this->callback(
                /**
                 * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
                 *
                 * @return bool
                 */
                    function ($storeCollectionTransfer) {
                        return $storeCollectionTransfer->getStores()[0]->getIdStore() === static::FIRST_STORE_ID &&
                            $storeCollectionTransfer->getStores()[1]->getIdStore() === static::SECOND_STORE_ID;
                    },
                ),
            )->willReturn(
                (new StoreCollectionTransfer())
                    ->addStore(
                        (new StoreTransfer())->setIdStore(static::FIRST_STORE_ID),
                    )->addStore(
                        (new StoreTransfer())->setIdStore(static::SECOND_STORE_ID)->setApplicationContextCollection(
                            (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                                (new StoreApplicationContextTransfer())->setTimezone('Europe/Berlin'),
                            ),
                        ),
                    ),
            );
        $plugin->setFacade($mockFacade);

        // Act
        $storeTransfersResult = $plugin->expand($storeTransfers);

        // Assert
        $this->assertIsArray($storeTransfersResult);
        $this->assertCount(2, $storeTransfersResult);
        $this->assertInstanceOf(StoreTransfer::class, $storeTransfersResult[0]);
        $this->assertInstanceOf(StoreTransfer::class, $storeTransfersResult[1]);
        $this->assertEquals(100, $storeTransfersResult[0]->getIdStore());
    }
}

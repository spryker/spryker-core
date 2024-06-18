<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContext\Communication\Plugin\Store;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreContext\Business\StoreContextFacade;
use Spryker\Zed\StoreContext\Communication\Plugin\Store\ContextStorePreUpdateValidationPlugin;
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
 * @group ContextStorePreUpdateValidationPluginTest
 * Add your own group annotations below this line
 */
class ContextStorePreUpdateValidationPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const DEFAULT_STORE_ID = 100;

    /**
     * @var \SprykerTest\Zed\StoreContext\StoreContextCommunicationTester
     */
    protected StoreContextCommunicationTester $tester;

    /**
     * @return void
     */
    public function testValidateReturnsCorrectStoreResponseTransfer(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setIdStore(static::DEFAULT_STORE_ID);
        $mockFacade = $this->createPartialMock(StoreContextFacade::class, ['validateStoreContextCollection']);
        $mockFacade->method('validateStoreContextCollection')
            ->with(
                $this->callback(
                    /**
                     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
                     *
                     * @return bool
                     */
                    function ($storeContextCollectionRequestTransfer) {
                        return count($storeContextCollectionRequestTransfer->getContexts()) === 1 && $storeContextCollectionRequestTransfer->getContexts()[0]->getStore()->getIdStore() === static::DEFAULT_STORE_ID;
                    },
                ),
            )->willReturn(
                (new StoreContextCollectionResponseTransfer())
                    ->addContext(
                        (new StoreContextTransfer())->setStore(
                            (new StoreTransfer())->setIdStore(static::DEFAULT_STORE_ID),
                        )->setApplicationContextCollection(
                            (new StoreApplicationContextCollectionTransfer()),
                        ),
                    ),
            );

        $plugin = new ContextStorePreUpdateValidationPlugin();
        $plugin->setFacade($mockFacade);

        // Act
        $storeResponseTransfer = $plugin->validate($storeTransfer);

        // Assert
        $this->assertEquals(static::DEFAULT_STORE_ID, $storeResponseTransfer->getStore()->getIdStore());
    }
}

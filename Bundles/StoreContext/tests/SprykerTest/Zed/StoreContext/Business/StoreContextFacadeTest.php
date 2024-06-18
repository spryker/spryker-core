<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContext\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use SprykerTest\Zed\StoreContext\StoreContextBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreContext
 * @group Business
 * @group Facade
 * @group StoreContextFacadeTest
 * Add your own group annotations below this line
 */
class StoreContextFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\StoreContext\StoreContextBusinessTester
     */
    protected StoreContextBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandStoreCollectionWithContextDataSuccessful(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([$this->tester::FIELD_STORE_NAME => $this->tester::STORE_NAME_XX]);
        $storeCollectionTransfer = (new StoreCollectionTransfer())->addStore($storeTransfer);
        $storeContextTransfer = $this->tester->haveStoreContext($storeTransfer->getIdStore());

        // Act
        $storeCollectionTransfer = $this->tester->createStoreContextFacade()->expandStoreCollection($storeCollectionTransfer);

        // Assert
        /**
         * @var \Generated\Shared\Transfer\StoreTransfer $extendedStoreTransfer
         */
        $extendedStoreTransfer = $storeCollectionTransfer->getStores()[0];
        $this->assertCount(1, $storeCollectionTransfer->getStores());
        $this->assertSame($storeTransfer->getIdStore(), $storeCollectionTransfer->getStores()[0]->getIdStore());
        $this->assertCount(count($storeContextTransfer->getApplicationContextCollection()->getApplicationContexts()), $extendedStoreTransfer->getApplicationContextCollection()->getApplicationContexts());
        $this->assertSame($this->tester::TIMEZONE_DEFAULT, $extendedStoreTransfer->getApplicationContextCollection()->getApplicationContexts()[0]->getTimezone());
    }

    /**
     * @return void
     */
    public function testExpandStoreCollectionIfStoreContextNotExistSuccessful(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([$this->tester::FIELD_STORE_NAME => $this->tester::STORE_NAME_XX]);
        $storeCollectionTranfer = (new StoreCollectionTransfer())->addStore($storeTransfer);

        // Act
        $storeCollectionTranfer = $this->tester->createStoreContextFacade()->expandStoreCollection($storeCollectionTranfer);

        // Assert
        $this->assertCount(1, $storeCollectionTranfer->getStores());
        $this->assertNull($storeCollectionTranfer->getStores()[0]->getApplicationContextCollection());
    }

    /**
     * @return void
     */
    public function testValidateStoreContextCollectionSuccessful(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([$this->tester::FIELD_STORE_NAME => $this->tester::STORE_NAME_XX]);
        $storeContextTransfer = $this->tester->haveStoreContext($storeTransfer->getIdStore());
        $storeContextCollectionRequestTransfer = (new StoreContextCollectionRequestTransfer())->addContext(
            (new StoreContextTransfer())->setStore($storeTransfer)->setApplicationContextCollection(
                $storeContextTransfer->getApplicationContextCollection(),
            ),
        );
        // Act
        $storeContextCollectionResponseTransfer = $this->tester->createStoreContextFacade()->validateStoreContextCollection($storeContextCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $storeContextCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCreateStoreContextCollectionReturnsResponseWithoutErrors(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([$this->tester::FIELD_STORE_NAME => $this->tester::STORE_NAME_XX]);
        $storeContextCollectionRequestTransfer = (new StoreContextCollectionRequestTransfer())->addContext(
            (new StoreContextTransfer())->setStore($storeTransfer)
                ->setApplicationContextCollection(
                    (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                        (new StoreApplicationContextTransfer())->setTimezone($this->tester::TIMEZONE_DEFAULT),
                    )->addApplicationContext(
                        (new StoreApplicationContextTransfer())
                            ->setTimezone($this->tester::TIMEZONE_DEFAULT)
                            ->setApplication($this->tester::APP_NAME),
                    ),
                ),
        );

        // Act
        $storeContextCollectionResponseTransfer = $this->tester->createStoreContextFacade()->createStoreContextCollection($storeContextCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $storeContextCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $storeContextCollectionResponseTransfer->getContexts());
    }

    /**
     * @return void
     */
    public function testCreateStoreContextCollectionReturnsErrorStoreContextAlreadyExists(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([$this->tester::FIELD_STORE_NAME => $this->tester::STORE_NAME_XX]);
        $storeContextCollectionRequestTransfer = (new StoreContextCollectionRequestTransfer())->addContext(
            (new StoreContextTransfer())->setStore($storeTransfer)
                ->setApplicationContextCollection(
                    (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                        (new StoreApplicationContextTransfer())->setTimezone($this->tester::TIMEZONE_DEFAULT),
                    )->addApplicationContext(
                        (new StoreApplicationContextTransfer())
                            ->setTimezone($this->tester::TIMEZONE_DEFAULT)
                            ->setApplication($this->tester::APP_NAME),
                    ),
                ),
        );
        $this->tester->createStoreContextFacade()->createStoreContextCollection($storeContextCollectionRequestTransfer);

        // Act
        $storeContextCollectionResponseTransfer = $this->tester->createStoreContextFacade()->createStoreContextCollection($storeContextCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $storeContextCollectionResponseTransfer->getErrors());
        $this->assertSame($this->tester::MESSAGE_STORE_CONTEXT_EXISTS, $storeContextCollectionResponseTransfer->getErrors()[0]->getMessage());
    }

    /**
     * @return void
     */
    public function testCreateStoreContextReturnErrorMessageStoreContextCollectionEmpty(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([$this->tester::FIELD_STORE_NAME => $this->tester::STORE_NAME_XX]);
        $storeContextCollectionRequestTransfer = (new StoreContextCollectionRequestTransfer())->addContext(
            (new StoreContextTransfer())->setStore($storeTransfer),
        );

        // Act
        $storeContextCollectionResponseTransfer = $this->tester->createStoreContextFacade()->createStoreContextCollection($storeContextCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $storeContextCollectionResponseTransfer->getErrors());
        $this->assertSame($this->tester::MESSAGE_STORE_CONTEXT_MISSING, $storeContextCollectionResponseTransfer->getErrors()[0]->getMessage());
    }

    /**
     * @return void
     */
    public function testUpdateStoreContextSuccessfull(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([$this->tester::FIELD_STORE_NAME => $this->tester::STORE_NAME_XX]);
        $storeContextCollectionRequestTransfer = (new StoreContextCollectionRequestTransfer())->addContext(
            (new StoreContextTransfer())->setStore($storeTransfer)
                ->setApplicationContextCollection(
                    (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                        (new StoreApplicationContextTransfer())->setTimezone($this->tester::TIMEZONE_DEFAULT),
                    )->addApplicationContext(
                        (new StoreApplicationContextTransfer())
                            ->setTimezone($this->tester::TIMEZONE_DEFAULT)
                            ->setApplication($this->tester::APP_NAME),
                    ),
                ),
        );
        $this->tester->createStoreContextFacade()->createStoreContextCollection($storeContextCollectionRequestTransfer);

        // Act
        $storeContextCollectionResponseTransfer = $this->tester->createStoreContextFacade()->updateStoreContextCollection($storeContextCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $storeContextCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $storeContextCollectionResponseTransfer->getContexts());
    }

    /**
     * @return void
     */
    public function testUpdateStoreContextReturnErrorMessageStoreContextCollectionEmpty(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([$this->tester::FIELD_STORE_NAME => $this->tester::STORE_NAME_XX]);
        $storeContextCollectionRequestTransfer = (new StoreContextCollectionRequestTransfer())->addContext(
            (new StoreContextTransfer())->setStore($storeTransfer)
                ->setApplicationContextCollection(
                    (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                        (new StoreApplicationContextTransfer())->setTimezone($this->tester::TIMEZONE_DEFAULT),
                    )->addApplicationContext(
                        (new StoreApplicationContextTransfer())
                            ->setTimezone($this->tester::TIMEZONE_DEFAULT)
                            ->setApplication($this->tester::APP_NAME),
                    ),
                ),
        );

        // Act
        $storeContextCollectionResponseTransfer = $this->tester->createStoreContextFacade()->updateStoreContextCollection($storeContextCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $storeContextCollectionResponseTransfer->getErrors());
        $this->assertSame($this->tester::MESSAGE_STORE_CONTEXT_DOESNT_EXIST, $storeContextCollectionResponseTransfer->getErrors()[0]->getMessage());
    }
}

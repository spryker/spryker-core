<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StoreContextStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Generated\Shared\Transfer\StoreStorageTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group StoreContextStorage
 * @group StoreContextStorageClientTest
 * Add your own group annotations below this line
 */
class StoreContextStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const TIMEZONE = 'Europe/Berlin';

    /**
     * @var \SprykerTest\Client\StoreContextStorage\StoreContextStorageClientTester
     */
    protected StoreContextStorageClientTester $tester;

    /**
     * @return void
     */
    public function testExpandStoreWithTimezoneExpandsStoreTransferWithTimezone(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreStorageTransfer::NAME => static::STORE_NAME]);
        $storeTransfer->setApplicationContextCollection((new StoreApplicationContextCollectionTransfer())->addApplicationContext(
            (new StoreApplicationContextTransfer())->setTimezone(static::TIMEZONE),
        ));

        $storeContextStorageClient = $this->tester->getLocator()->storeContextStorage()->client();

        // Act
        $storeTransfer = $storeContextStorageClient->expandStoreWithTimezone($storeTransfer);

        // Assert
        $this->assertNotNull($storeTransfer->getTimezone());
        $this->assertSame(static::TIMEZONE, $storeTransfer->getTimezone());
    }
}

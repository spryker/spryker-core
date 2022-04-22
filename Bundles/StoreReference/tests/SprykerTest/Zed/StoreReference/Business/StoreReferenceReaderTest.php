<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreReference\Business;

use Codeception\Test\Unit;
use Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreReference
 * @group Business
 * @group StoreReferenceReaderTest
 * Add your own group annotations below this line
 */
class StoreReferenceReaderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\StoreReference\StoreReferenceBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetStoreByStoreReferenceReturnsExpectedTransferWhenInputArgumentIsCorrect(): void
    {
        // Arrange
        $storeReference = 'dev-DE';
        $storeName = 'DE';
        $this->tester->setStoreReferenceData([$storeName => $storeReference]);

        // Act
        $storeTransfer = $this->tester->getFacade()->getStoreByStoreReference($storeReference);

        // Assert
        $this->assertEquals($storeReference, $storeTransfer->getStoreReference());
        $this->assertEquals($storeName, $storeTransfer->getName());
    }

    /**
     * @return void
     */
    public function testGetStoreByStoreReferenceThrowsExceptionWhenInputArgumentIsNotCorrect(): void
    {
        // Arrange
        $invalidStoreReference = '1';

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->tester->getFacade()->getStoreByStoreReference($invalidStoreReference);
    }

    /**
     * @return void
     */
    public function testGetStoreByStoreNameReturnsExpectedTransferWhenInputArgumentIsCorrect(): void
    {
        // Arrange
        $storeReference = 'dev-AT';
        $storeName = 'AT';
        $this->tester->setStoreReferenceData([$storeName => $storeReference]);

        // Act
        $storeTransfer = $this->tester->getFacade()->getStoreByStoreName($storeName);

        // Assert
        $this->assertEquals($storeReference, $storeTransfer->getStoreReference());
        $this->assertEquals($storeName, $storeTransfer->getName());
    }

    /**
     * @return void
     */
    public function testGetStoreByStoreNameThrowsExceptionWhenInputArgumentIsNotCorrect(): void
    {
        // Arrange
        $invalidStoreName = '1';

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->tester->getFacade()->getStoreByStoreName($invalidStoreName);
    }
}

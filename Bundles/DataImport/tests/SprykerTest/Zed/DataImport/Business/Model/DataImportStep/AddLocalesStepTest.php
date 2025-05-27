<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataImportStep;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataImportStep
 * @group AddLocalesStepTest
 * Add your own group annotations below this line
 */
class AddLocalesStepTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep
     */
    protected AddLocalesStep $addLocalesStep;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected DataImportToStoreFacadeInterface|MockObject $storeFacadeMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->createAddLocalesStep();
    }

    /**
     * @return void
     */
    public function testAddSameLanguageLocalesForDifferentStores(): void
    {
        // Arrange
        $storeTransfers = [
            $this->tester->buildStoreTransfer([
                StoreTransfer::NAME => 'DE',
                StoreTransfer::AVAILABLE_LOCALE_ISO_CODES => [
                    'de' => 'de_DE',
                    'en' => 'en_US',
                ],
            ]),
            $this->tester->buildStoreTransfer([
                StoreTransfer::NAME => 'AT',
                StoreTransfer::AVAILABLE_LOCALE_ISO_CODES => [
                    'de' => 'de_AT',
                    'en' => 'en_US',
                ],
            ]),
        ];
        $this->storeFacadeMock
            ->method('getAllStores')
            ->willReturn($storeTransfers);

        $dataSet = new DataSet();

        // Act
        $this->addLocalesStep->execute($dataSet);

        // Assert
        $this->assertArrayHasKey(AddLocalesStep::KEY_LOCALES, $dataSet);
        $this->assertCount(3, $dataSet[AddLocalesStep::KEY_LOCALES]);
        $this->assertArrayHasKey('de_DE', $dataSet[AddLocalesStep::KEY_LOCALES]);
        $this->assertArrayHasKey('de_AT', $dataSet[AddLocalesStep::KEY_LOCALES]);
        $this->assertArrayHasKey('en_US', $dataSet[AddLocalesStep::KEY_LOCALES]);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep
     */
    protected function createAddLocalesStep(): AddLocalesStep
    {
        $this->storeFacadeMock = $this->mockStoreFacade();
        $this->addLocalesStep = new AddLocalesStep($this->storeFacadeMock);

        return $this->addLocalesStep;
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function mockStoreFacade(): DataImportToStoreFacadeInterface|MockObject
    {
        return $this->createMock(DataImportToStoreFacadeInterface::class);
    }
}

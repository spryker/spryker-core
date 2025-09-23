<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImportMerchant\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\DataImportMerchant\DataImportMerchantDependencyProvider;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\PossibleCsvHeaderExpanderPluginInterface;
use SprykerTest\Zed\DataImportMerchant\DataImportMerchantBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImportMerchant
 * @group Business
 * @group Facade
 * @group GetPossibleCsvHeadersIndexedByImporterTypeTest
 * Add your own group annotations below this line
 */
class GetPossibleCsvHeadersIndexedByImporterTypeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DataImportMerchant\DataImportMerchantBusinessTester
     */
    protected DataImportMerchantBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldThrowsExceptionWhenRequiredFieldIsMissing(): void
    {
        // Arrange
        $merchantTransfer = new MerchantTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->getPossibleCsvHeadersIndexedByImporterType($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testShouldExecutePossibleCsvHeaderExpanderPluginStack(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());

        // Assert
        $possibleCsvHeaderExpanderPluginMock = $this->createMock(PossibleCsvHeaderExpanderPluginInterface::class);
        $possibleCsvHeaderExpanderPluginMock
            ->expects($this->once())
            ->method('expand');

        $this->tester->setDependency(
            DataImportMerchantDependencyProvider::PLUGINS_POSSIBLE_CSV_HEADER_EXPANDER,
            [$possibleCsvHeaderExpanderPluginMock],
        );

        // Act
        $this->tester
            ->getFacade()
            ->getPossibleCsvHeadersIndexedByImporterType((new MerchantTransfer())->setMerchantReference('Spryker'));
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataExport\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use SprykerTest\Zed\DataExport\DataExportBusinessTester;
use Throwable;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataExport
 * @group Business
 * @group Facade
 * @group ExportDataEntitiesTest
 * Add your own group annotations below this line
 */
class ExportDataEntitiesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DataExport\DataExportBusinessTester
     */
    protected DataExportBusinessTester $tester;

    /**
     * @return void
     */
    public function testThrowsAnExceptionWhenThrowExceptionOptionIsProvided(): void
    {
        // Arrange
        $dataExportConfigurationsTransfer = (new DataExportConfigurationsTransfer())
            ->addAction(new DataExportConfigurationTransfer())
            ->setThrowException(true);

        // Assert
        $this->expectException(Throwable::class);

        // Act
        $this->tester->getFacade()->exportDataEntities($dataExportConfigurationsTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNotThrowAnExceptionWhenThrowExceptionOptionIsNotProvided(): void
    {
        // Arrange
        $dataExportConfigurationsTransfer = (new DataExportConfigurationsTransfer())
            ->addAction(new DataExportConfigurationTransfer())
            ->setThrowException(false);

        // Act
        $dataExportReportTransfers = $this->tester->getFacade()->exportDataEntities($dataExportConfigurationsTransfer);

        // Assert
        $this->assertCount(0, $dataExportReportTransfers);
    }
}

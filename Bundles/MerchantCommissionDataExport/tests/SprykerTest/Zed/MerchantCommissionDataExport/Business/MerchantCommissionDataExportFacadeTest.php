<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommissionDataExport\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionAmountBuilder;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Service\DataExport\Plugin\DataExport\OutputStreamDataExportConnectionPlugin;
use SprykerTest\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommissionDataExport
 * @group Business
 * @group Facade
 * @group MerchantCommissionDataExportFacadeTest
 * Add your own group annotations below this line
 */
class MerchantCommissionDataExportFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Service\DataExport\DataExportDependencyProvider::DATA_EXPORT_CONNECTION_PLUGINS
     *
     * @var string
     */
    protected const DATA_EXPORT_CONNECTION_PLUGINS = 'DATA_EXPORT_CONNECTION_PLUGINS';

    /**
     * @var \SprykerTest\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportBusinessTester
     */
    protected MerchantCommissionDataExportBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantCommissionTableIsEmpty();
        $this->tester->setDependency(static::DATA_EXPORT_CONNECTION_PLUGINS, [
            new OutputStreamDataExportConnectionPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testExportMerchantCommissionResultTransferWithCorrectData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $merchantTransfer = $this->tester->haveMerchant();
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $merchantCommissionGroupTransfer = $this->tester->haveMerchantCommissionGroup();
        $merchantCommissionAmountTransfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => $currencyTransfer,
        ]))->build();
        $merchantCommissionTransfer = $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => [$merchantTransfer->toArray()],
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [$merchantCommissionAmountTransfer->toArray()],
        ]);

        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer();

        // Act
        ob_start();
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantCommission($dataExportConfigurationTransfer);
        $exportedData = ob_get_clean();

        // Assert
        $this->assertTrue($dataExportReportTransfer->getIsSuccessful());
        $this->assertCount(1, $dataExportReportTransfer->getDataExportResults());

        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->getIterator()->current();
        $this->assertSame(1, $dataExportResultTransfer->getExportCount());

        $parsedExportedData = $this->tester->parseExportedData($exportedData);
        $this->assertCount(1, $parsedExportedData);
        $this->assertSame($merchantCommissionTransfer->getKeyOrFail(), $parsedExportedData[0]['key']);
    }
}

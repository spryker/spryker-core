<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantOmsDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\MerchantOmsDataImport\Communication\Plugin\DataImport\MerchantOmsProcessDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOmsDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantOmsProcessDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantOmsProcessDataImportPluginTest extends Unit
{
    public const MERCHANT_REFERENCE = 'merchant-profile-data-import-test-reference';

    /**
     * @var \SprykerTest\Zed\MerchantOmsDataImport\MerchantOmsDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMerchantOmsProcessImport(): void
    {
        // Arrange
        if (!$this->tester->findMerchantByReference(static::MERCHANT_REFERENCE)) {
            $this->tester->haveMerchant([
                'merchant_reference' => static::MERCHANT_REFERENCE,
            ]);
        }

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant_oms_process.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $merchantOmsProcessDataImportPlugin = new MerchantOmsProcessDataImportPlugin();
        $dataImporterReportTransfer = $merchantOmsProcessDataImportPlugin->import($dataImportConfigurationTransfer);
        $merchantEntity = $this->tester->findMerchantByReference(static::MERCHANT_REFERENCE);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertNotNull($merchantEntity->getFkStateMachineProcess());
        $this->tester->assertStateMachineProcessDatabaseTableContainsData();
    }
}

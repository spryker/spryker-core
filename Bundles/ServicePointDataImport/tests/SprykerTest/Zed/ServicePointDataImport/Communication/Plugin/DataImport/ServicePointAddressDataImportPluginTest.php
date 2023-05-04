<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ServicePointDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServicePointAddressDataImportPlugin;
use SprykerTest\Zed\ServicePointDataImport\ServicePointDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ServicePointAddressDataImportPluginTest
 * Add your own group annotations below this line
 */
class ServicePointAddressDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePointDataImport\ServicePointDataImportCommunicationTester
     */
    protected ServicePointDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServicePointTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->haveServicePoint([ServicePointTransfer::KEY => 'sp1']);
        $this->tester->haveServicePoint([ServicePointTransfer::KEY => 'sp2']);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/service_point_address.csv');

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $servicePointAddressDataImportPlugin = new ServicePointAddressDataImportPlugin();
        $dataImporterReportTransfer = $servicePointAddressDataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertCount(2, $this->tester->getServicePointAddressQuery());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Arrange
        $servicePointAddressDataImportPlugin = new ServicePointAddressDataImportPlugin();

        // Act
        $importType = $servicePointAddressDataImportPlugin->getImportType();

        // Assert
        $this->assertSame('service-point-address', $importType);
    }
}

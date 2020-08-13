<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ConfigurableBundleDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ConfigurableBundleDataImport\Communication\Plugin\ConfigurableBundleTemplateDataImportPlugin;
use Spryker\Zed\ConfigurableBundleDataImport\ConfigurableBundleDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleDataImport
 * @group Communication
 * @group Plugin
 * @group ConfigurableBundleTemplateDataImportPluginTest
 * Add your own group annotations below this line
 * @group ConfigurableBundles
 */
class ConfigurableBundleTemplateDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundleDataImport\ConfigurableBundleDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureConfigurableBundleTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/configurable_bundle_template.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $configurableBundleTemplateDataImportPlugin = new ConfigurableBundleTemplateDataImportPlugin();
        $dataImporterReportTransfer = $configurableBundleTemplateDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertConfigurableBundleTemplateDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $configurableBundleTemplateDataImportPlugin = new ConfigurableBundleTemplateDataImportPlugin();

        // Assert
        $this->assertSame(ConfigurableBundleDataImportConfig::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE, $configurableBundleTemplateDataImportPlugin->getImportType());
    }
}

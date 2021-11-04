<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\AclEntityDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\AclEntityDataImport\AclEntityDataImportConfig;
use Spryker\Zed\AclEntityDataImport\Communication\Plugin\AclEntitySegmentDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntityDataImport
 * @group Communication
 * @group Plugin
 * @group AclEntitySegmentDataImportPluginTest
 * Add your own group annotations below this line
 */
class AclEntitySegmentDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 5;

    /**
     * @var \SprykerTest\Zed\AclEntityDataImport\AclEntityDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsAclEntitySegment(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . 'import/acl_entity_segment.csv');

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer);

        // Act
        $dataImportPlugin = new AclEntitySegmentDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return void
     */
    public function testImportImportsAclEntitySegmentWithEmptyName(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . 'import/acl_entity_segment_empty_name.csv');

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Field "name" cannot be empty');

        // Act
        (new AclEntitySegmentDataImportPlugin())->import($dataImporterConfigurationTransfer);
    }

    /**
     * @dataProvider importImportsAclEntitySegmentWithEmptyRequiredFieldProvider
     *
     * @param string $importFile
     * @param string $expectedExceptionMessage
     *
     * @return void
     */
    public function testImportImportsAclEntitySegmentWithEmptyRequiredField(
        string $importFile,
        string $expectedExceptionMessage
    ): void {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . $importFile);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        // Act
        (new AclEntitySegmentDataImportPlugin())->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return array<array<\string>>
     */
    public function importImportsAclEntitySegmentWithEmptyRequiredFieldProvider(): array
    {
        return [
            ['import/acl_entity_segment_empty_name.csv', 'Field "name" cannot be empty'],
            ['import/acl_entity_segment_empty_reference.csv', 'Field "reference" cannot be empty'],
        ];
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = new AclEntitySegmentDataImportPlugin();

        // Act
        $importType = $dataImportPlugin->getImportType();

        // Assert
        $this->assertEquals(AclEntityDataImportConfig::IMPORT_TYPE_ACL_ENTITY_SEGMENT, $importType);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->tester->deleteAclEntitySegments();
    }
}

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
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\AclEntityDataImport\AclEntityDataImportConfig;
use Spryker\Zed\AclEntityDataImport\Communication\Plugin\AclEntitySegmentConnectorDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntityDataImport
 * @group Communication
 * @group Plugin
 * @group AclEntitySegmentConnectorDataImportPluginTest
 * Add your own group annotations below this line
 */
class AclEntitySegmentConnectorDataImportPluginTest extends Unit
{
    protected const EXPECTED_IMPORT_ENTITY_COUNT = 2;

    protected const MERCHANT_REFERENCE_1 = 'yoJaVJStcddyqgDXyPJJ';
    protected const MERCHANT_REFERENCE_2 = 'nnMAzpnVaCHXnnefKBwX';

    protected const ACL_ENTITY_SEGMENT_REFERENCE_1 = 'VZ70yrh8KdWtozCNSwnDw';
    protected const ACL_ENTITY_SEGMENT_REFERENCE_2 = '7bJYglxII28vs9ZNoWurw';

    protected const ACL_ENTITY_SEGMENT_NAME_1 = 'Segment name 1';
    protected const ACL_ENTITY_SEGMENT_NAME_2 = 'Segment name 2';

    protected const ACL_ENTITY_SEGMENT_CONNECTOR_IMPORT_FILE_NAME = 'import/acl_entity_segment_connector.csv';
    protected const ACL_ENTITY_SEGMENT_CONNECTOR_IMPORT_FILE_NAME_INVALID_DATA_ENTITY = 'import/acl_entity_segment_connector_invalid_data_entity.csv';
    protected const ACL_ENTITY_SEGMENT_CONNECTOR_IMPORT_FILE_NAME_INVALID_ENTITY_REFERENCE = 'import/acl_entity_segment_connector_invalid_entity_reference.csv';
    protected const ACL_ENTITY_SEGMENT_CONNECTOR_IMPORT_FILE_NAME_INVALID_SEGMENT_REFERENCE = 'import/acl_entity_segment_connector_invalid_segment_reference.csv';

    /**
     * @var \SprykerTest\Zed\AclEntityDataImport\AclEntityDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsAclEntitySegmentConnectorDataSuccessfully(): void
    {
        $this->markTestSkipped();

        // Arrange
        $this->tester->generateAclEntitySegments();
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1]);
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_2]);

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::ACL_ENTITY_SEGMENT_CONNECTOR_IMPORT_FILE_NAME);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer);

        // Act
        $dataImportPlugin = new AclEntitySegmentConnectorDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_ENTITY_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return void
     */
    public function testImportThrowsDataImportExceptionWhenImportDataContainsInvalidDataEntity(): void
    {
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(
            'Referenced entity class was not found: Orm\Zed\Product\Persistence\InvalidEntity'
        );

        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(
            codecept_data_dir() . static::ACL_ENTITY_SEGMENT_CONNECTOR_IMPORT_FILE_NAME_INVALID_DATA_ENTITY
        );

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImportPlugin = new AclEntitySegmentConnectorDataImportPlugin();
        $dataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsDataImportExceptionWhenImportDataContainsInvalidEntityReference(): void
    {
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(
            'Failed to find Orm\Zed\Merchant\Persistence\SpyMerchant by merchant_reference: "nonexistentkuDXyPJJx"'
        );

        // Arrange
        $this->tester->generateAclEntitySegments();
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_2]);

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(
            codecept_data_dir() . static::ACL_ENTITY_SEGMENT_CONNECTOR_IMPORT_FILE_NAME_INVALID_ENTITY_REFERENCE
        );

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImportPlugin = new AclEntitySegmentConnectorDataImportPlugin();
        $dataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsDataImportExceptionWhenImportDataContainsInvalidSegmentReference(): void
    {
        $this->markTestSkipped();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(
            'Failed to find Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment by reference: "nonexistentsegmentreference"'
        );

        // Arrange
        $this->tester->generateAclEntitySegments();

        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1]);
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_2]);

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(
            codecept_data_dir() . static::ACL_ENTITY_SEGMENT_CONNECTOR_IMPORT_FILE_NAME_INVALID_SEGMENT_REFERENCE
        );

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImportPlugin = new AclEntitySegmentConnectorDataImportPlugin();
        $dataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    /**
     * @dataProvider importThrowsDataImportExceptionWhenImportDataDoesntContainsRequiredFieldProvider
     *
     * @param string $importFile
     * @param string $expectedExceptionMessage
     *
     * @return void
     */
    public function testImportThrowsDataImportExceptionWhenImportDataDoesntContainsRequiredField(
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
        (new AclEntitySegmentConnectorDataImportPlugin())->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return \string[][]
     */
    public function importThrowsDataImportExceptionWhenImportDataDoesntContainsRequiredFieldProvider(): array
    {
        return [
            [
                'import/acl_entity_segment_connector_empty_data_entity.csv',
                'Field "data_entity" cannot be empty',
            ],
            [
                'import/acl_entity_segment_connector_empty_entity_reference.csv',
                'Field "entity_reference" cannot be empty',
            ],
            [
                'import/acl_entity_segment_connector_empty_reference_field.csv',
                'Field "reference_field" cannot be empty',
            ],
            [
                'import/acl_entity_segment_connector_empty_acl_entity_segment_reference.csv',
                'Field "acl_entity_segment_reference" cannot be empty',
            ],
        ];
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = new AclEntitySegmentConnectorDataImportPlugin();

        // Act
        $importType = $dataImportPlugin->getImportType();

        // Assert
        $this->assertEquals(AclEntityDataImportConfig::IMPORT_TYPE_ACL_ENTITY_SEGMENT_CONNECTOR, $importType);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->tester->cleanUpEntitySegmentConnectors();
        $this->tester->deleteAclEntitySegments();
        $this->tester->deleteMerchants(
            [
                static::MERCHANT_REFERENCE_1,
                static::MERCHANT_REFERENCE_2,
            ]
        );
    }
}

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\AclDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Spryker\Zed\AclDataImport\AclDataImportConfig;
use Spryker\Zed\AclDataImport\Communication\Plugin\AclGroupDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclDataImport
 * @group Communication
 * @group Plugin
 * @group AclGroupDataImportPluginTest
 * Add your own group annotations below this line
 */
class AclGroupDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var string
     */
    protected const ACL_GROUP_NAME_1 = 'Foo group';
    /**
     * @var string
     */
    protected const ACL_GROUP_NAME_2 = 'Bar group';

    /**
     * @var \SprykerTest\Zed\AclRoleDataImport\AclDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsAclGroup(): void
    {
        // Arrange
        $this->tester->deleteGroups((
            new GroupCriteriaTransfer())->setNames([static::ACL_GROUP_NAME_1, static::ACL_GROUP_NAME_2]));

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/acl_group.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImportPlugin = $this->getAclGroupDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return void
     */
    public function testImportImportsAclGroupWithEmptyName(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/acl_group_empty_name.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Field "name" cannot be empty');

        // Act
        $this->getAclGroupDataImportPlugin()->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = $this->getAclGroupDataImportPlugin();

        // Act
        $importType = $dataImportPlugin->getImportType();

        // Assert
        $this->assertEquals(AclDataImportConfig::IMPORT_TYPE_ACL_GROUP, $importType);
    }

    /**
     * @return \Spryker\Zed\AclDataImport\Communication\Plugin\AclGroupDataImportPlugin
     */
    protected function getAclGroupDataImportPlugin(): AclGroupDataImportPlugin
    {
        return new AclGroupDataImportPlugin();
    }
}

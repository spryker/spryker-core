<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\AclDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclRoleCriteriaTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\AclDataImport\AclDataImportConfig;
use Spryker\Zed\AclDataImport\Communication\Plugin\AclRoleDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclDataImport
 * @group Communication
 * @group Plugin
 * @group AclRoleDataImportPluginTest
 * Add your own group annotations below this line
 */
class AclRoleDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 3;

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_1 = 'User';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_2 = 'Moderator';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_3 = 'Administrator';

    /**
     * @var \SprykerTest\Zed\AclRoleDataImport\AclDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsAclRole(): void
    {
        // Arrange
        $this->tester->deleteRoles(
            (new AclRoleCriteriaTransfer())->setNames(
                [static::ACL_ROLE_NAME_1, static::ACL_ROLE_NAME_2, static::ACL_ROLE_NAME_3],
            ),
        );
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/acl_role.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImportPlugin = $this->getAclRoleDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @group foo
     *
     * @return void
     */
    public function testImportImportsAclRoleWithEmptyName(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/acl_role_empty_name.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Field "name" cannot be empty');

        // Act
        ($this->getAclRoleDataImportPlugin())->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = $this->getAclRoleDataImportPlugin();

        // Act
        $importType = $dataImportPlugin->getImportType();

        // Assert
        $this->assertEquals(AclDataImportConfig::IMPORT_TYPE_ACL_ROLE, $importType);
    }

    /**
     * @return \Spryker\Zed\AclDataImport\Communication\Plugin\AclRoleDataImportPlugin
     */
    protected function getAclRoleDataImportPlugin(): AclRoleDataImportPlugin
    {
        return new AclRoleDataImportPlugin();
    }
}

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
use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\AclDataImport\AclDataImportConfig;
use Spryker\Zed\AclDataImport\Communication\Plugin\AclGroupRoleDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclDataImport
 * @group Communication
 * @group Plugin
 * @group AclGroupRoleDataImportPluginTest
 * Add your own group annotations below this line
 */
class AclGroupRoleDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ACL_GROUP_NAME_1 = 'Group foo';

    /**
     * @var string
     */
    protected const ACL_GROUP_NAME_2 = 'Group bar';

    /**
     * @var string
     */
    protected const ACL_GROUP_REFERENCE_1 = 'group_foo';

    /**
     * @var string
     */
    protected const ACL_GROUP_REFERENCE_2 = 'group_bar';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_1 = 'Role foo';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_2 = 'Role bar';

    /**
     * @var string
     */
    protected const ACL_ROLE_REFERENCE_1 = 'role_foo';

    /**
     * @var string
     */
    protected const ACL_ROLE_REFERENCE_2 = 'role_bar';

    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var \SprykerTest\Zed\AclRoleDataImport\AclDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsAclGroupRole(): void
    {
        // Arrange
        $this->tester->deleteRoles(
            (new AclRoleCriteriaTransfer())->setNames([static::ACL_ROLE_NAME_1, static::ACL_ROLE_NAME_2])
        );
        $this->tester->deleteGroups(
            (new GroupCriteriaTransfer())->setNames([static::ACL_GROUP_NAME_1, static::ACL_GROUP_NAME_2])
        );

        $this->tester->haveGroup(
            [
                GroupTransfer::NAME => static::ACL_GROUP_NAME_1,
                GroupTransfer::REFERENCE => static::ACL_GROUP_REFERENCE_1,
            ]
        );
        $this->tester->haveGroup(
            [
                GroupTransfer::NAME => static::ACL_GROUP_NAME_2,
                GroupTransfer::REFERENCE => static::ACL_GROUP_REFERENCE_2,
            ]
        );
        $this->tester->haveRole(
            [
                RoleTransfer::NAME => static::ACL_ROLE_NAME_1,
                RoleTransfer::REFERENCE => static::ACL_ROLE_REFERENCE_1,
            ]
        );
        $this->tester->haveRole(
            [
                RoleTransfer::NAME => static::ACL_ROLE_NAME_2,
                RoleTransfer::REFERENCE => static::ACL_ROLE_REFERENCE_2,
            ]
        );

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/acl_group_role.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImportPlugin = $this->getAclGroupRoleDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return void
     */
    public function testImportImportsAclGroupRoleWithEmptyGroupReference(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/acl_group_role_empty_group_reference.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $dataImportConfigurationTransfer->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Field "group_reference" cannot be empty');

        // Act
        $this->getAclGroupRoleDataImportPlugin()->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportImportsAclGroupRoleWithEmptyRoleReference(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/acl_group_role_empty_role_reference.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Field "role_reference" cannot be empty');

        // Act
        $this->getAclGroupRoleDataImportPlugin()->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = $this->getAclGroupRoleDataImportPlugin();

        // Act
        $importType = $dataImportPlugin->getImportType();

        // Assert
        $this->assertEquals(AclDataImportConfig::IMPORT_TYPE_ACL_GROUP_ROLE, $importType);
    }

    /**
     * @return \Spryker\Zed\AclDataImport\Communication\Plugin\AclGroupRoleDataImportPlugin
     */
    protected function getAclGroupRoleDataImportPlugin(): AclGroupRoleDataImportPlugin
    {
        return new AclGroupRoleDataImportPlugin();
    }
}

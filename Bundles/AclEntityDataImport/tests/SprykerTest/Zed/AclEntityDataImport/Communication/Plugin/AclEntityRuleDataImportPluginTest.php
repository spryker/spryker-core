<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\AclEntityDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclRoleCriteriaTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntityDataImport\AclEntityDataImportConfig;
use Spryker\Zed\AclEntityDataImport\Communication\Plugin\AclEntityRuleDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntityDataImport
 * @group Communication
 * @group Plugin
 * @group AclEntityRuleDataImportPluginTest
 * Add your own group annotations below this line
 */
class AclEntityRuleDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 5;

    /**
     * @var string
     */
    protected const ACL_ROLE_REFERENCE_1 = 'GK9rS4jIzVP3Jq71iqNY';

    /**
     * @var string
     */
    protected const ACL_ROLE_REFERENCE_2 = 'SIfdvYi3rZwxNKJRehEm';

    /**
     * @var string
     */
    protected const ACL_ROLE_REFERENCE_3 = 'sCrVK2mGyV77B7uifjPI';

    /**
     * @var string
     */
    protected const ACL_ROLE_REFERENCE_4 = 'rV3aEVTp66WBE46c1tPN';

    /**
     * @var string
     */
    protected const ACL_ROLE_REFERENCE_5 = 'e73vG9wnDKI6rzuIJIts';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_1 = 'Role 1';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_2 = 'Role 2';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_3 = 'Role 3';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_4 = 'Role 4';

    /**
     * @var string
     */
    protected const ACL_ROLE_NAME_5 = 'Role 5';

    /**
     * @var string
     */
    protected const ACL_ENTITY_RULE_ENTITY_1 = 'Orm\Zed\Company\Persistence\SpyCompany';

    /**
     * @var string
     */
    protected const ACL_ENTITY_RULE_ENTITY_2 = 'Orm\Zed\Product\Persistence\SpyProductAbstract';

    /**
     * @var string
     */
    protected const ACL_ENTITY_RULE_ENTITY_3 = 'Orm\Zed\Product\Persistence\SpyProduct';

    /**
     * @var string
     */
    protected const ACL_ENTITY_RULE_ENTITY_4 = 'Orm\Zed\CmsBlock\Persistence\SpyCmsBlock';

    /**
     * @var string
     */
    protected const ACL_ENTITY_RULE_ENTITY_5 = 'Orm\Zed\Customer\Persistence\SpyCustomer';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_REFERENCE_1 = 'sH9qLMZtt6sxWqRJVYib';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_REFERENCE_2 = '5nIYY1SETa50lSDiwxf8';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_NAME_1 = 'Segment 1';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_NAME_2 = 'Segment 2';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_TARGET_ENTITY = 'Orm\Zed\Merchant\Persistence\SpyMerchant';

    /**
     * @var \SprykerTest\Zed\AclEntityDataImport\AclEntityDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->deleteAclRoles();
    }

    /**
     * @return void
     */
    public function testImportImportsAclEntityRule(): void
    {
        // Arrange
        $this->generateAclRoles();

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . 'import/acl_entity_rule.csv');

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer);

        // Act
        $dataImportPlugin = new AclEntityRuleDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());

        $aclRoleEntity1 = $this->tester->getAclRole(static::ACL_ROLE_REFERENCE_1);
        $aclRoleEntity2 = $this->tester->getAclRole(static::ACL_ROLE_REFERENCE_2);
        $aclRoleEntity3 = $this->tester->getAclRole(static::ACL_ROLE_REFERENCE_3);
        $aclRoleEntity4 = $this->tester->getAclRole(static::ACL_ROLE_REFERENCE_4);
        $aclRoleEntity5 = $this->tester->getAclRole(static::ACL_ROLE_REFERENCE_5);

        $aclEntityRuleEntity1 = $this->tester->getAclEntityRule(
            $aclRoleEntity1->getIdAclRole(),
            static::ACL_ENTITY_RULE_ENTITY_1,
            AclEntityConstants::SCOPE_GLOBAL,
        );
        $aclEntityRuleEntity2 = $this->tester->getAclEntityRule(
            $aclRoleEntity2->getIdAclRole(),
            static::ACL_ENTITY_RULE_ENTITY_2,
            AclEntityConstants::SCOPE_GLOBAL,
        );
        $aclEntityRuleEntity3 = $this->tester->getAclEntityRule(
            $aclRoleEntity3->getIdAclRole(),
            static::ACL_ENTITY_RULE_ENTITY_3,
            AclEntityConstants::SCOPE_GLOBAL,
        );
        $aclEntityRuleEntity4 = $this->tester->getAclEntityRule(
            $aclRoleEntity4->getIdAclRole(),
            static::ACL_ENTITY_RULE_ENTITY_4,
            AclEntityConstants::SCOPE_GLOBAL,
        );
        $aclEntityRuleEntity5 = $this->tester->getAclEntityRule(
            $aclRoleEntity5->getIdAclRole(),
            static::ACL_ENTITY_RULE_ENTITY_5,
            AclEntityConstants::SCOPE_GLOBAL,
        );

        $this->assertSame(AclEntityConstants::OPERATION_MASK_CREATE, $aclEntityRuleEntity1->getPermissionMask());
        $this->assertSame(AclEntityConstants::OPERATION_MASK_READ, $aclEntityRuleEntity2->getPermissionMask());
        $this->assertSame(AclEntityConstants::OPERATION_MASK_UPDATE, $aclEntityRuleEntity3->getPermissionMask());
        $this->assertSame(AclEntityConstants::OPERATION_MASK_DELETE, $aclEntityRuleEntity4->getPermissionMask());
        $this->assertSame(
            AclEntityConstants::OPERATION_MASK_CREATE
                | AclEntityConstants::OPERATION_MASK_READ
                | AclEntityConstants::OPERATION_MASK_UPDATE
                | AclEntityConstants::OPERATION_MASK_DELETE,
            $aclEntityRuleEntity5->getPermissionMask(),
        );
    }

    /**
     * @return void
     */
    public function testImportImportsAclEntityRuleWithIncorrectRoleReference(): void
    {
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find AclRole by reference: "incorrectReference"');

        // Arrange
        $this->generateAclRoles();

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . 'import/acl_entity_rule_incorrect_role_reference.csv');

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImportPlugin = new AclEntityRuleDataImportPlugin();
        $dataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportImportsAclEntityRuleWithIncorrectSegmentReference(): void
    {
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find AclEntitySegment by reference: "incorrectReference"');

        // Arrange
        $this->generateAclRoles();

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . 'import/acl_entity_rule_incorrect_segment_reference.csv');

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImportPlugin = new AclEntityRuleDataImportPlugin();
        $dataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    /**
     * @dataProvider importImportsAclEntityRuleWithEmptyRequiredFieldProvider
     *
     * @param string $importFile
     * @param string $expectedExceptionMessage
     *
     * @return void
     */
    public function testImportImportsAclEntityRuleWithEmptyRequiredField(string $importFile, string $expectedExceptionMessage): void
    {
        // Arrange
        $this->generateAclRoles();
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . $importFile);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        // Act
        (new AclEntityRuleDataImportPlugin())->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return array<array<\string>>
     */
    public function importImportsAclEntityRuleWithEmptyRequiredFieldProvider(): array
    {
        return [
            ['import/acl_entity_rule_empty_acl_role_reference.csv', 'Field "acl_role_reference" cannot be empty'],
            ['import/acl_entity_rule_empty_entity.csv', 'Field "entity" cannot be empty'],
            ['import/acl_entity_rule_empty_permission_mask.csv', 'Field "permission_mask" cannot be empty'],
            ['import/acl_entity_rule_empty_scope.csv', 'Field "scope" cannot be empty'],
        ];
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = new AclEntityRuleDataImportPlugin();

        // Act
        $importType = $dataImportPlugin->getImportType();

        // Assert
        $this->assertEquals(AclEntityDataImportConfig::IMPORT_TYPE_ACL_ENTITY_RULE, $importType);
    }

    /**
     * @return void
     */
    protected function generateAclRoles(): void
    {
        $this->tester->haveRole(
            [
                RoleTransfer::REFERENCE => static::ACL_ROLE_REFERENCE_1,
                RoleTransfer::NAME => static::ACL_ROLE_NAME_1,
            ],
        );
        $this->tester->haveRole(
            [
                RoleTransfer::REFERENCE => static::ACL_ROLE_REFERENCE_2,
                RoleTransfer::NAME => static::ACL_ROLE_NAME_2,
            ],
        );
        $this->tester->haveRole(
            [
                RoleTransfer::REFERENCE => static::ACL_ROLE_REFERENCE_3,
                RoleTransfer::NAME => static::ACL_ROLE_NAME_3,
            ],
        );
        $this->tester->haveRole(
            [
                RoleTransfer::REFERENCE => static::ACL_ROLE_REFERENCE_4,
                RoleTransfer::NAME => static::ACL_ROLE_NAME_4,
            ],
        );
        $this->tester->haveRole(
            [
                RoleTransfer::REFERENCE => static::ACL_ROLE_REFERENCE_5,
                RoleTransfer::NAME => static::ACL_ROLE_NAME_5,
            ],
        );
    }

    /**
     * @return void
     */
    protected function deleteAclRoles(): void
    {
        $this->tester->deleteRoles(
            (new AclRoleCriteriaTransfer())->setNames(
                [
                    static::ACL_ROLE_NAME_1,
                    static::ACL_ROLE_NAME_2,
                    static::ACL_ROLE_NAME_3,
                    static::ACL_ROLE_NAME_4,
                    static::ACL_ROLE_NAME_5,
                ],
            ),
        );
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->deleteAclRoles();
        $this->tester->deleteAclEntitySegments();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\CompanyFile\Permission;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentCriteriaPermissionExpander;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group CompanyFile
 * @group Permission
 * @group FileAttachmentPermissionExpanderTest
 */
class FileAttachmentPermissionExpanderTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_COMPANY = 1;

    /**
     * @var int
     */
    protected const ID_COMPANY_BUSINESS_UNIT = 2;

    /**
     * @var int
     */
    protected const ID_COMPANY_USER = 3;

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentCriteriaPermissionExpander|\PHPUnit\Framework\MockObject\MockObject
     */
    protected FileAttachmentCriteriaPermissionExpander|MockObject $fileAttachmentPermissionExpanderMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            PermissionDependencyProvider::PLUGINS_PERMISSION,
            [
                new ViewCompanyFilesPermissionPlugin(),
                new ViewCompanyBusinessUnitFilesPermissionPlugin(),
                new ViewCompanyUserFilesPermissionPlugin(),
                new ViewCompanySspAssetPermissionPlugin(),
                new ViewBusinessUnitSspAssetPermissionPlugin(),
            ],
        );

        $this->fileAttachmentPermissionExpanderMock = $this->createPartialMock(
            FileAttachmentCriteriaPermissionExpander::class,
            ['can'],
        );
    }

    /**
     * @return void
     */
    public function testExpandWithoutCompanyUserReturnsUnchangedCriteria(): void
    {
        // Arrange
        $fileAttachmentCriteriaTransfer = new FileAttachmentCriteriaTransfer();

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame($fileAttachmentCriteriaTransfer, $result);
        $this->assertNull($result->getCompanyUser());
    }

    /**
     * @return void
     */
    public function testExpandWithCompanyFilesPermissionSetsCompanyIdFilter(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithSspAssetRelation(true);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => true,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY], $result->getFileAttachmentConditions()->getCompanyIds());
        $this->assertTrue($result->getWithCompanyRelation());
        $this->assertTrue($result->getWithBusinessUnitRelation());
        $this->assertFalse($result->getWithSspAssetRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithBusinessUnitFilesPermissionSetsBusinessUnitIdFilter(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithSspAssetRelation(true);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => true,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY_BUSINESS_UNIT], $result->getFileAttachmentConditions()->getBusinessUnitIds());
        $this->assertFalse($result->getWithCompanyRelation());
        $this->assertNull($result->getWithBusinessUnitRelation());
        $this->assertFalse($result->getWithSspAssetRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithCompanyUserFilesPermissionSetsCompanyUserIdFilter(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithSspAssetRelation(true);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => false,
            ViewCompanyUserFilesPermissionPlugin::KEY => true,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY_USER], $result->getFileAttachmentConditions()->getCompanyUserIds());
        $this->assertFalse($result->getWithCompanyRelation());
        $this->assertFalse($result->getWithBusinessUnitRelation());
        $this->assertFalse($result->getWithSspAssetRelation());
        $this->assertNull($result->getWithCompanyUserRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithCompanySspAssetPermissionSetsCompanyIdFilter(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => false,
            ViewCompanyUserFilesPermissionPlugin::KEY => false,
            ViewCompanySspAssetPermissionPlugin::KEY => true,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY], $result->getFileAttachmentConditions()->getSspAssetCompanyIds());
        $this->assertNull($result->getWithSspAssetRelation());
        $this->assertFalse($result->getWithCompanyRelation());
        $this->assertFalse($result->getWithBusinessUnitRelation());
        $this->assertFalse($result->getWithCompanyUserRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithBusinessUnitSspAssetPermissionSetsBusinessUnitIdFilter(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => false,
            ViewCompanyUserFilesPermissionPlugin::KEY => false,
            ViewCompanySspAssetPermissionPlugin::KEY => false,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => true,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertNotEmpty($result->getFileAttachmentConditions()->getSspAssetBusinessUnitIds());
        $this->assertNull($result->getWithSspAssetRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithoutAnyPermissionsDisablesAllRelations(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => false,
            ViewCompanyUserFilesPermissionPlugin::KEY => false,
            ViewCompanySspAssetPermissionPlugin::KEY => false,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => false,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertFalse($result->getWithCompanyRelation());
        $this->assertFalse($result->getWithBusinessUnitRelation());
        $this->assertFalse($result->getWithSspAssetRelation());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getBusinessUnitIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyUserIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getSspAssetCompanyIds());
    }

    /**
     * @return void
     */
    public function testExpandWithMultiplePermissionsPrioritizesCompanyLevel(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => true,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => true,
            ViewCompanyUserFilesPermissionPlugin::KEY => true,
            ViewCompanySspAssetPermissionPlugin::KEY => true,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => true,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY], $result->getFileAttachmentConditions()->getCompanyIds());
        $this->assertSame([static::ID_COMPANY], $result->getFileAttachmentConditions()->getSspAssetCompanyIds());

        $this->assertEmpty($result->getFileAttachmentConditions()->getBusinessUnitIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyUserIds());
    }

    /**
     * @return void
     */
    public function testExpandWithBusinessUnitAndUserPermissionsButNoCompanyPermission(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => true,
            ViewCompanyUserFilesPermissionPlugin::KEY => true,
            ViewCompanySspAssetPermissionPlugin::KEY => false,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => false,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY_BUSINESS_UNIT], $result->getFileAttachmentConditions()->getBusinessUnitIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyUserIds());

        $this->assertFalse($result->getWithCompanyRelation());

        $this->assertFalse($result->getWithSspAssetRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithOnlyUserPermissionSetsUserIdFilter(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => false,
            ViewCompanyUserFilesPermissionPlugin::KEY => true,
            ViewCompanySspAssetPermissionPlugin::KEY => false,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => false,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY_USER], $result->getFileAttachmentConditions()->getCompanyUserIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getBusinessUnitIds());

        $this->assertFalse($result->getWithCompanyRelation());
        $this->assertFalse($result->getWithBusinessUnitRelation());

        $this->assertFalse($result->getWithSspAssetRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithOnlySspAssetPermissionsIgnoresFilePermissions(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => false,
            ViewCompanyUserFilesPermissionPlugin::KEY => false,
            ViewCompanySspAssetPermissionPlugin::KEY => true,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => false,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY], $result->getFileAttachmentConditions()->getSspAssetCompanyIds());

        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getBusinessUnitIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyUserIds());

        $this->assertFalse($result->getWithCompanyRelation());
        $this->assertFalse($result->getWithBusinessUnitRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithMixedFileAndSspAssetPermissions(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer())
            ->setWithCompanyUserRelation(true)
            ->setWithSspAssetRelation(true);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => true,
            ViewCompanyUserFilesPermissionPlugin::KEY => false,
            ViewCompanySspAssetPermissionPlugin::KEY => false,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => true,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY_BUSINESS_UNIT], $result->getFileAttachmentConditions()->getBusinessUnitIds());

        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyUserIds());

        $this->assertEmpty($result->getFileAttachmentConditions()->getSspAssetCompanyIds());
        $this->assertNotEmpty($result->getFileAttachmentConditions()->getSspAssetBusinessUnitIds());

        $this->assertFalse($result->getWithCompanyRelation());
        $this->assertTrue($result->getWithCompanyUserRelation());
        $this->assertNull($result->getWithBusinessUnitRelation());
        $this->assertTrue($result->getWithSspAssetRelation());
    }

    /**
     * @return void
     */
    public function testExpandWithCompanyFileAndBusinessUnitSspAssetPermissions(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserTransfer();
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => true,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => false,
            ViewCompanyUserFilesPermissionPlugin::KEY => false,
            ViewCompanySspAssetPermissionPlugin::KEY => false,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => true,
        ]);

        // Act
        $result = $this->fileAttachmentPermissionExpanderMock->expand($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertSame([static::ID_COMPANY], $result->getFileAttachmentConditions()->getCompanyIds());

        $this->assertEmpty($result->getFileAttachmentConditions()->getBusinessUnitIds());
        $this->assertEmpty($result->getFileAttachmentConditions()->getCompanyUserIds());

        $this->assertEmpty($result->getFileAttachmentConditions()->getSspAssetCompanyIds());
        $this->assertNotEmpty($result->getFileAttachmentConditions()->getSspAssetBusinessUnitIds());

        $this->assertNull($result->getWithSspAssetRelation());
    }

    /**
     * @param array<string, bool> $permissions
     *
     * @return void
     */
    protected function mockPermissions(array $permissions): void
    {
        $this->fileAttachmentPermissionExpanderMock
            ->method('can')
            ->willReturnCallback(function (string $permissionKey, int $companyUserId) use ($permissions): bool {
                return $permissions[$permissionKey] ?? false;
            });
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(): CompanyUserTransfer
    {
        $companyTransfer = (new CompanyTransfer())
            ->setIdCompany(static::ID_COMPANY);

        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit(static::ID_COMPANY_BUSINESS_UNIT);

        return (new CompanyUserTransfer())
            ->setIdCompanyUser(static::ID_COMPANY_USER)
            ->setCompany($companyTransfer)
            ->setCompanyBusinessUnit($companyBusinessUnitTransfer);
    }
}

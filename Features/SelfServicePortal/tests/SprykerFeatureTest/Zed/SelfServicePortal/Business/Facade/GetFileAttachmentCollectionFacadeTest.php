<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentSearchConditionsTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Shared\FileSystem\FileSystemConstants;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentCriteriaPermissionExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepository;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group GetFileAttachmentCollectionFacadeTest
 * Add your own group annotations below this line
 */
class GetFileAttachmentCollectionFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected const EXTENSION_PDF = 'pdf';

    /**
     * @var string
     */
    protected const FILE_NAME_SEARCH = 'test_search_name.pdf';

    /**
     * @var string
     */
    protected const FILE_REFERENCE_SEARCH = 'test_search_reference';

    /**
     * @var string
     */
    protected const FILE_NAME_OTHER = 'other.pdf';

    /**
     * @var string
     */
    protected const FILE_REFERENCE_OTHER = 'other_ref';

    /**
     * @var string
     */
    public const PLUGIN_COLLECTION_FILESYSTEM_BUILDER = 'filesystem builder plugin collection';

    /**
     * @var string
     */
    protected const PLUGIN_WRITER = 'PLUGIN_WRITER';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected SelfServicePortalBusinessTester $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface
     */
    protected SelfServicePortalFacadeInterface $facade;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentCriteriaPermissionExpander|\PHPUnit\Framework\MockObject\MockObject
     */
    protected FileAttachmentCriteriaPermissionExpander|MockObject $fileAttachmentPermissionExpanderMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->tester->setDependency(static::PLUGIN_WRITER, new FileSystemWriterPlugin());
        $this->tester->setDependency(static::PLUGIN_COLLECTION_FILESYSTEM_BUILDER, [
            new LocalFilesystemBuilderPlugin(),
        ]);

        $localFilesystemBuilderConfiguration = [
            'sprykerAdapterClass' => LocalFilesystemBuilderPlugin::class,
            'root' => '/data/data/tmp/ssp-files',
            'path' => '/',
        ];

        $this->tester->setConfig(FileSystemConstants::FILESYSTEM_SERVICE, [
            's3-import' => $localFilesystemBuilderConfiguration,
            'files' => $localFilesystemBuilderConfiguration,
        ]);

        $this->tester->ensureFileAttachmentTablesAreEmpty();

        $this->facade = $this->tester->getFacade();

        $this->fileAttachmentPermissionExpanderMock = $this->createPartialMock(
            FileAttachmentCriteriaPermissionExpander::class,
            ['can'],
        );
    }

    /**
     * @return void
     */
    public function testGetFilesAttachmentsForCompanyWithPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $this->tester->haveCompanyFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => true,
        ]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(true);

        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $companyUserTransfer
            ->setCompany($companyTransfer)
            ->setCompanyBusinessUnit($businessUnitTransfer);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentsForSspAssetWithPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS =>
            [[SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer]],
        ]);

        $this->tester->haveCompanyFileAttachment(
            [
                'idFile' => $fileTransfer->getIdFileOrFail(),
                'idCompany' => $companyTransfer->getIdCompanyOrFail(),
            ],
        );

        $this->tester->haveSspAssetFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idSspAsset' => $sspAssetTransfer->getIdSspAssetOrFail(),
        ]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithSspAssetRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFilesAttachmentsForCompanyWithoutPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $emptyPermissionCollectionTransfer = new PermissionCollectionTransfer();
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $companyTransfer,
            $emptyPermissionCollectionTransfer,
        );

        $companyUserTransfer->setCompany($companyTransfer);

        $this->tester->haveCompanyFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(false)
            ->setWithBusinessUnitRelation(false)
            ->setWithCompanyUserRelation(false)
            ->setWithSspAssetRelation(false);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        $this->assertCount(0, $fileAttachmentCollectionTransfer->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testGetFilesAttachmentsForCompanyUserWithPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $this->mockPermissions([
            ViewCompanyUserFilesPermissionPlugin::KEY => true,
        ]);

        $this->tester->haveCompanyUserFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompanyUser' => $companyUserTransfer->getIdCompanyUserOrFail(),
        ]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyUserRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFilesAttachmentsForBusinessUnitWithPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $fileTransfer = $this->tester->haveFile();
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);
        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $this->mockPermissions([
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => true,
        ]);

        $this->tester->haveCompanyBusinessUnitFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompanyBusinessUnit' => $businessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithBusinessUnitRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFilesAttachmentsWithMixedPermissionLevels(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);
        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => true,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => true,
            ViewCompanyUserFilesPermissionPlugin::KEY => true,
        ]);

        $companyFileTransfer = $this->tester->haveFile();
        $businessUnitFileTransfer = $this->tester->haveFile();
        $userFileTransfer = $this->tester->haveFile();

        // Create attachments at different levels
        $this->tester->haveCompanyFileAttachment([
            'idFile' => $companyFileTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $this->tester->haveCompanyBusinessUnitFileAttachment([
            'idFile' => $businessUnitFileTransfer->getIdFileOrFail(),
            'idCompanyBusinessUnit' => $businessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $this->tester->haveCompanyUserFileAttachment([
            'idFile' => $userFileTransfer->getIdFileOrFail(),
            'idCompanyUser' => $companyUserTransfer->getIdCompanyUserOrFail(),
        ]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithCompanyUserRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        $this->assertCount(3, $fileAttachmentCollectionTransfer->getFileAttachments());

        $returnedFileIds = [];
        foreach ($fileAttachmentCollectionTransfer->getFileAttachments() as $fileAttachment) {
            $returnedFileIds[] = $fileAttachment->getFile()->getIdFile();
        }

        $this->assertContains($companyFileTransfer->getIdFile(), $returnedFileIds);
        $this->assertContains($businessUnitFileTransfer->getIdFile(), $returnedFileIds);
        $this->assertContains($userFileTransfer->getIdFile(), $returnedFileIds);
    }

    /**
     * @return void
     */
    public function testGetFilesAttachmentsWithMixedEntityTypes(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);
        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => true,
            ViewBusinessUnitSspAssetPermissionPlugin::KEY => true,
        ]);

        $companyFileTransfer = $this->tester->haveFile();
        $sspAssetFileTransfer = $this->tester->haveFile();

        $this->tester->haveCompanyFileAttachment([
            'idFile' => $companyFileTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);

        $this->tester->haveSspAssetFileAttachment([
            'idFile' => $sspAssetFileTransfer->getIdFileOrFail(),
            'idSspAsset' => $sspAssetTransfer->getIdSspAssetOrFail(),
        ]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithSspAssetRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        $this->assertCount(2, $fileAttachmentCollectionTransfer->getFileAttachments());

        $returnedFileIds = [];
        foreach ($fileAttachmentCollectionTransfer->getFileAttachments() as $fileAttachment) {
            $returnedFileIds[] = $fileAttachment->getFile()->getIdFile();
        }

        $this->assertContains($companyFileTransfer->getIdFile(), $returnedFileIds);
        $this->assertContains($sspAssetFileTransfer->getIdFile(), $returnedFileIds);
    }

    /**
     * @return void
     */
    public function testGetFilesAttachmentsWithPartialPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);
        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $this->mockPermissions([
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => true,
        ]);

        $companyFileTransfer = $this->tester->haveFile();
        $businessUnitFileTransfer = $this->tester->haveFile();
        $userFileTransfer = $this->tester->haveFile();

        $this->tester->haveCompanyFileAttachment([
            'idFile' => $companyFileTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $this->tester->haveCompanyBusinessUnitFileAttachment([
            'idFile' => $businessUnitFileTransfer->getIdFileOrFail(),
            'idCompanyBusinessUnit' => $businessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $this->tester->haveCompanyUserFileAttachment([
            'idFile' => $userFileTransfer->getIdFileOrFail(),
            'idCompanyUser' => $companyUserTransfer->getIdCompanyUserOrFail(),
        ]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(false)
            ->setWithBusinessUnitRelation(true)
            ->setWithCompanyUserRelation(false)
            ->setWithSspAssetRelation(false);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert - Should return only business unit file
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $businessUnitFileTransfer->getIdFile(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
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

        $factoryMock = $this->createPartialMock(SelfServicePortalBusinessFactory::class, ['createFileAttachmentPermissionExpander']);
        $factoryMock->setRepository((new SelfServicePortalRepository()));
        $factoryMock->method('createFileAttachmentPermissionExpander')->willReturn($this->fileAttachmentPermissionExpanderMock);
        $this->facade->setFactory($factoryMock);
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentsReturnsEmptyCollectionWhenNoFilesExist(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer())
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithCompanyUserRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionTransfer->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentsFilteredByDateRange(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $currentTime = time();
        $fileTransfer = $this->tester->haveFile([], [
            FileInfoTransfer::CREATED_AT => (new DateTime())->createFromFormat(static::DATE_TIME_FORMAT, $currentTime),
        ]);
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $currentTime = time();

        $this->tester->haveCompanyFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $rangeTransfer = (new CriteriaRangeFilterTransfer())
            ->setFrom($currentTime - 3600)
            ->setTo($currentTime + 3600);

        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())
            ->setRangeCreatedAt($rangeTransfer);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithCompanyUserRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade
            ->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentsFilteredByFileType(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile([], [
            FileInfoTransfer::EXTENSION => static::EXTENSION_PDF,
        ]);

        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $this->tester->haveCompanyFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())
            ->setFileTypes([static::EXTENSION_PDF]);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithCompanyUserRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade
            ->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentsFilteredBySearchString(): void
    {
        $fileByNameTransfer = $this->tester->haveFile([
            FileTransfer::FILE_NAME => static::FILE_NAME_SEARCH,
        ]);
        $fileByReferenceTransfer = $this->tester->haveFile([
            FileTransfer::FILE_REFERENCE => static::FILE_REFERENCE_SEARCH,
        ]);
        $fileNotMatchingTransfer = $this->tester->haveFile([
            FileTransfer::FILE_NAME => static::FILE_NAME_OTHER,
            FileTransfer::FILE_REFERENCE => static::FILE_REFERENCE_OTHER,
        ]);
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $this->tester->haveCompanyFileAttachment([
            'idFile' => $fileByNameTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);
        $this->tester->haveCompanyFileAttachment([
            'idFile' => $fileByReferenceTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);
        $this->tester->haveCompanyFileAttachment([
            'idFile' => $fileNotMatchingTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $fileAttachmentSearchConditionsTransfer = (new FileAttachmentSearchConditionsTransfer())
            ->setSearchString('test_search');

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions(new FileAttachmentConditionsTransfer())
            ->setFileAttachmentSearchConditions($fileAttachmentSearchConditionsTransfer)
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithCompanyUserRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(2, $fileAttachmentCollectionTransfer->getFileAttachments());

        $fileId1 = $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile();
        $fileId2 = $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(1)->getFile()->getIdFile();
        $this->assertContains($fileByNameTransfer->getIdFile(), [$fileId1, $fileId2]);
        $this->assertContains($fileByReferenceTransfer->getIdFile(), [$fileId1, $fileId2]);
        $this->assertNotContains($fileNotMatchingTransfer->getIdFile(), [$fileId1, $fileId2]);
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentCollectionWithCompanyPermissionAndBusinessUnitFilterShouldReturnFiles(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $customerBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $companyUserTransfer
            ->setCompany($companyTransfer)
            ->setCompanyBusinessUnit($customerBusinessUnitTransfer);

        $fileTransfer = $this->tester->haveFile();
        $this->tester->haveCompanyBusinessUnitFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompanyBusinessUnit' => $businessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $this->tester->haveCompanyUserFileAttachment([
            'idFile' => $this->tester->haveFile()->getIdFileOrFail(),
            'idCompanyUser' => $companyUserTransfer->getIdCompanyUserOrFail(),
        ]);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => true,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => false,
        ]);

        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())
            ->addBusinessUnitUuid($businessUnitTransfer->getUuidOrFail());

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentCollectionWithBusinessUnitPermissionAndBusinessUnitFilterShouldNotReturnFiles(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $customerBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $companyUserTransfer
            ->setCompany($companyTransfer)
            ->setCompanyBusinessUnit($customerBusinessUnitTransfer);

        $fileTransfer = $this->tester->haveFile();
        $this->tester->haveCompanyBusinessUnitFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompanyBusinessUnit' => $businessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $this->mockPermissions([
            ViewCompanyFilesPermissionPlugin::KEY => false,
            ViewCompanyBusinessUnitFilesPermissionPlugin::KEY => true,
        ]);

        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())
            ->addBusinessUnitUuid($businessUnitTransfer->getUuidOrFail());

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer)
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->setWithBusinessUnitRelation(true);

        // Act
        $fileAttachmentCollectionTransfer = $this->facade->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionTransfer->getFileAttachments());
    }
}

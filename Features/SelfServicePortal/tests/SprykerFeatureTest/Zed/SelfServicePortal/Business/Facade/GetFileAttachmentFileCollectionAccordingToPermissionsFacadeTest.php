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
use Generated\Shared\Transfer\FileAttachmentFileConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileSearchConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Shared\FileSystem\FileSystemConstants;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group GetFileAttachmentFileCollectionAccordingToPermissionsFacadeTest
 * Add your own group annotations below this line
 */
class GetFileAttachmentFileCollectionAccordingToPermissionsFacadeTest extends Unit
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
    }

    /**
     * @return void
     */
    public function testGetFilesAccordingToPermissionsReturnsFilesForCompanyWithPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentFileConditionsTransfer = (new FileAttachmentFileConditionsTransfer())
            ->setEntityTypes([SelfServicePortalConfig::ENTITY_TYPE_COMPANY]);

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setFileAttachmentFileConditions($fileAttachmentFileConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer());

        // Act
        $fileAttachmentFileCollectionTransfer = $this->facade->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentFileCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentFileCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFilesAccordingToPermissionsReturnsFilesForSspAssetWithPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);
        $businessUnitTransfer->setCompany($companyTransfer);
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);
        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS =>
            [[SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer]],
        ]);

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
        ]);

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $sspAssetTransfer->getIdSspAsset(),
            FileAttachmentTransfer::ENTITY_NAME => SelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET,
        ]);

        $fileAttachmentFileConditionsTransfer = (new FileAttachmentFileConditionsTransfer())
            ->setEntityTypes([SelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET]);

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setFileAttachmentFileConditions($fileAttachmentFileConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer());

        // Act
        $fileAttachmentFileCollectionTransfer = $this->facade->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentFileCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentFileCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFilesAccordingToPermissionsReturnsEmptyCollectionWhenNoFilesExist(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileConditions(new FileAttachmentFileConditionsTransfer())
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer());

        $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditions()->setEntityTypes([
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER,
        ]);

        // Act
        $fileAttachmentFileCollectionTransfer = $this->facade->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentFileCollectionTransfer->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testGetFilesAccordingToPermissionsFiltersFilesByDateRange(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $currentTime = time();
        $fileTransfer = $this->tester->haveFile([], [
            FileInfoTransfer::CREATED_AT => (new DateTime())->createFromFormat(static::DATE_TIME_FORMAT, $currentTime),
        ]);
        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);
        $currentTime = time();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
        ]);

        $rangeTransfer = (new CriteriaRangeFilterTransfer())
            ->setFrom($currentTime - 3600)
            ->setTo($currentTime + 3600);

        $fileAttachmentFileConditionsTransfer = (new FileAttachmentFileConditionsTransfer())
            ->setRangeCreatedAt($rangeTransfer);

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setFileAttachmentFileConditions($fileAttachmentFileConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer());

        $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditions()->setEntityTypes([
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER,
        ]);

        // Act
        $fileAttachmentFileCollectionTransfer = $this->facade
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentFileCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentFileCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFilesAccordingToPermissionsFiltersFilesByFileType(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile([], [
            FileInfoTransfer::EXTENSION => static::EXTENSION_PDF,
        ]);

        $companyUserTransfer = $this->tester->createCompanyUser($companyTransfer);

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentFileConditionsTransfer = (new FileAttachmentFileConditionsTransfer())
            ->setFileTypes([static::EXTENSION_PDF]);

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setFileAttachmentFileConditions($fileAttachmentFileConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer());

        $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditions()->setEntityTypes([
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER,
        ]);

        // Act
        $fileAttachmentFileCollectionTransfer = $this->facade
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentFileCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentFileCollectionTransfer->getFileAttachments()->offsetGet(0)->getFile()->getIdFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetFilesAccordingToPermissionsFiltersFilesBySearchString(): void
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

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileByNameTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
        ]);
        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileByReferenceTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
        ]);
        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileNotMatchingTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentFileSearchConditionsTransfer = (new FileAttachmentFileSearchConditionsTransfer())
            ->setSearchString('test_search');

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileConditions(new FileAttachmentFileConditionsTransfer())
            ->setFileAttachmentFileSearchConditions($fileAttachmentFileSearchConditionsTransfer);

        $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditions()->setEntityTypes([
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY,
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER,
        ]);

        // Act
        $fileAttachmentFileCollectionTransfer = $this->facade
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(2, $fileAttachmentFileCollectionTransfer->getFileAttachments());
        $fileIds = array_map(
            function ($fileTransfer) {
                return $fileTransfer->getFile()->getIdFile();
            },
            $fileAttachmentFileCollectionTransfer->getFileAttachments()->getArrayCopy(),
        );
        $this->assertContains($fileByNameTransfer->getIdFile(), $fileIds);
        $this->assertContains($fileByReferenceTransfer->getIdFile(), $fileIds);
        $this->assertNotContains($fileNotMatchingTransfer->getIdFile(), $fileIds);
    }
}

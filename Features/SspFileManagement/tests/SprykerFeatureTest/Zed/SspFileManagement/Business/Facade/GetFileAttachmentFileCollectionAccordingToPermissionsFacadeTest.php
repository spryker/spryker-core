<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspFileManagement\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\FileAttachmentFileConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileSearchConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;
use SprykerFeatureTest\Zed\SspFileManagement\SspFileManagementBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspFileManagement
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
     * @var \SprykerFeatureTest\Zed\SspFileManagement\SspFileManagementBusinessTester
     */
    protected SspFileManagementBusinessTester $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->tester->setDependency(static::PLUGIN_WRITER, new FileSystemWriterPlugin());
        $this->tester->setDependency(static::PLUGIN_COLLECTION_FILESYSTEM_BUILDER, [
            new LocalFilesystemBuilderPlugin(),
        ]);
        $this->tester->ensureFileAttachmentTablesAreEmpty();
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
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentFileConditionsTransfer = (new FileAttachmentFileConditionsTransfer())
            ->setEntityTypes([SspFileManagementConfig::ENTITY_TYPE_COMPANY]);

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setFileAttachmentFileConditions($fileAttachmentFileConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer());

        // Act
        $fileAttachmentFileCollectionTransfer = $this->tester->getFacade()
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentFileCollectionTransfer->getFiles());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentFileCollectionTransfer->getFiles()->offsetGet(0)->getIdFile(),
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

        // Act
        $fileAttachmentFileCollectionTransfer = $this->tester->getFacade()
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentFileCollectionTransfer->getFiles());
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
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $rangeTransfer = (new CriteriaRangeFilterTransfer())
            ->setFrom($currentTime - 3600) // 1 hour ago
            ->setTo($currentTime + 3600); // 1 hour in future

        $fileAttachmentFileConditionsTransfer = (new FileAttachmentFileConditionsTransfer())
            ->setRangeCreatedAt($rangeTransfer);

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setFileAttachmentFileConditions($fileAttachmentFileConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer());

        // Act
        $fileAttachmentFileCollectionTransfer = $this->tester->getFacade()
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentFileCollectionTransfer->getFiles());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentFileCollectionTransfer->getFiles()->offsetGet(0)->getIdFile(),
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
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentFileConditionsTransfer = (new FileAttachmentFileConditionsTransfer())
            ->setFileTypes([static::EXTENSION_PDF]);

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setFileAttachmentFileConditions($fileAttachmentFileConditionsTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer());

        // Act
        $fileAttachmentFileCollectionTransfer = $this->tester->getFacade()
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentFileCollectionTransfer->getFiles());
        $this->assertSame(
            $fileTransfer->getIdFile(),
            $fileAttachmentFileCollectionTransfer->getFiles()->offsetGet(0)->getIdFile(),
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
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);
        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileByReferenceTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);
        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileNotMatchingTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentFileSearchConditionsTransfer = (new FileAttachmentFileSearchConditionsTransfer())
            ->setSearchString('test_search');

        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setFileAttachmentFileConditions(new FileAttachmentFileConditionsTransfer())
            ->setFileAttachmentFileSearchConditions($fileAttachmentFileSearchConditionsTransfer);

        // Act
        $fileAttachmentFileCollectionTransfer = $this->tester->getFacade()
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        // Assert
        $this->assertCount(2, $fileAttachmentFileCollectionTransfer->getFiles());
        $fileIds = array_map(
            function ($fileTransfer) {
                return $fileTransfer->getIdFile();
            },
            $fileAttachmentFileCollectionTransfer->getFiles()->getArrayCopy(),
        );
        $this->assertContains($fileByNameTransfer->getIdFile(), $fileIds);
        $this->assertContains($fileByReferenceTransfer->getIdFile(), $fileIds);
        $this->assertNotContains($fileNotMatchingTransfer->getIdFile(), $fileIds);
    }
}

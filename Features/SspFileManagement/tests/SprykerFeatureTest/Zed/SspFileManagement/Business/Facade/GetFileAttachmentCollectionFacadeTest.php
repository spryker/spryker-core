<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspFileManagement\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
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
 * @group GetFileAttachmentCollectionFacadeTest
 * Add your own group annotations below this line
 */
class GetFileAttachmentCollectionFacadeTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspFileManagement\SspFileManagementBusinessTester
     */
    protected SspFileManagementBusinessTester $tester;

    /**
     * @var string
     */
    protected const PLUGIN_WRITER = 'PLUGIN_WRITER';

    /**
     * @var string
     */
    public const PLUGIN_COLLECTION_FILESYSTEM_BUILDER = 'filesystem builder plugin collection';

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
    public function testGetFileAttachmentCollectionByCompanyIdShouldReturnOneAttachment(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())
            ->addIdFile($fileTransfer->getIdFile());

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer);

        // Act
        $fileAttachmentCollectionTransfer = $this->tester->getFacade()
            ->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionTransfer->getFileAttachments());
        $this->assertSame(
            $companyTransfer->getIdCompany(),
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getEntityId(),
        );
        $this->assertSame(
            SspFileManagementConfig::ENTITY_TYPE_COMPANY,
            $fileAttachmentCollectionTransfer->getFileAttachments()->offsetGet(0)->getEntityName(),
        );
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentCollectionShouldReturnEmptyCollection(): void
    {
        // Arrange
        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())
            ->addIdFile(0);

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer);

        // Act
        $fileAttachmentCollectionTransfer = $this->tester->getFacade()
            ->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionTransfer->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testGetFileAttachmentCollectionShouldReturnCollectionWithMultipleResults(): void
    {
        // Arrange
        $companyTransfer1 = $this->tester->haveCompany();
        $companyTransfer2 = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer1->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer2->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())
            ->addIdFile($fileTransfer->getIdFile());

        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer);

        // Act
        $fileAttachmentCollectionTransfer = $this->tester->getFacade()
            ->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        // Assert
        $this->assertCount(2, $fileAttachmentCollectionTransfer->getFileAttachments());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspFileManagement\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
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
 * @group SaveFileAttachmentCollectionFacadeTest
 * Add your own group annotations below this line
 */
class SaveFileAttachmentCollectionFacadeTest extends Unit
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
    public function testSaveFileAttachmentCollectionWithSingleAttachmentShouldSucceed(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $fileAttachment = (new FileAttachmentTransfer())
            ->setFile($fileTransfer)
            ->setEntityId($companyTransfer->getIdCompany())
            ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY);

        $fileAttachmentCollectionRequestTransfer = (new FileAttachmentCollectionRequestTransfer())
            ->setIdFile($fileTransfer->getIdFile())
            ->addFileAttachment($fileAttachment);

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()
            ->saveFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionResponseTransfer->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testSaveFileAttachmentCollectionWithMultipleAttachmentsShouldSucceed(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $fileAttachment1 = (new FileAttachmentTransfer())
            ->setFile($fileTransfer)
            ->setEntityId($companyTransfer->getIdCompany())
            ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY);

        $fileAttachment2 = (new FileAttachmentTransfer())
            ->setFile($fileTransfer)
            ->setEntityId($companyTransfer->getIdCompany())
            ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY);

        $fileAttachmentCollectionRequestTransfer = (new FileAttachmentCollectionRequestTransfer())
            ->setIdFile($fileTransfer->getIdFile())
            ->addFileAttachment($fileAttachment1)
            ->addFileAttachment($fileAttachment2);

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()
            ->saveFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $fileAttachmentCollectionResponseTransfer->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testSaveFileAttachmentCollectionWhenExistingAttachmentsHaveOneLessRecord(): void
    {
        // Arrange
        $companyTransfer1 = $this->tester->haveCompany();
        $companyTransfer2 = $this->tester->haveCompany();
        $companyTransfer3 = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $fileAttachment1 = $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer1->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachment2 = $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer2->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachment3 = (new FileAttachmentTransfer())
            ->setFile($fileTransfer)
            ->setEntityId($companyTransfer3->getIdCompany())
            ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY);

        $newCollectionRequest = (new FileAttachmentCollectionRequestTransfer())
            ->setIdFile($fileTransfer->getIdFile())
            ->addFileAttachment($fileAttachment1)
            ->addFileAttachment($fileAttachment2)
            ->addFileAttachment($fileAttachment3);

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()
            ->saveFileAttachmentCollection($newCollectionRequest);

        // Assert
        $this->assertCount(3, $fileAttachmentCollectionResponseTransfer->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testSaveFileAttachmentCollectionWhenExistingAttachmentsHaveDifferences(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();
        $newCompanyTransfer = $this->tester->haveCompany();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $newFileAttachment = (new FileAttachmentTransfer())
            ->setFile($fileTransfer)
            ->setEntityId($newCompanyTransfer->getIdCompany())
            ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY);

        $newCollectionRequest = (new FileAttachmentCollectionRequestTransfer())
            ->setIdFile($fileTransfer->getIdFile())
            ->addFileAttachment($newFileAttachment);

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()
            ->saveFileAttachmentCollection($newCollectionRequest);

        // Assert
        $this->assertCount(1, $fileAttachmentCollectionResponseTransfer->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testSaveFileAttachmentCollectionWhenExistingAttachmentsNotEmptyAndNewCollectionEmpty(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $emptyCollectionRequest = (new FileAttachmentCollectionRequestTransfer())
            ->setIdFile($fileTransfer->getIdFile());

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()
            ->saveFileAttachmentCollection($emptyCollectionRequest);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionResponseTransfer->getFileAttachments());
    }
}

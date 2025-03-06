<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspFileManagement\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
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
 * @group DeleteFileAttachmentCollectionFacade
 * Add your own group annotations below this line
 */
class DeleteFileAttachmentCollectionFacadeTest extends Unit
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
    public function testDeleteFileAttachmentCollectionByFileIds(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentCollectionDeleteCriteriaTransfer = (new FileAttachmentCollectionDeleteCriteriaTransfer())
            ->addIdFile($fileTransfer->getIdFile());

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionResponseTransfer->getErrors());
        $this->assertSame(0, $this->tester->countFileAttachmentsByIdFile($fileTransfer->getIdFile()));
    }

    /**
     * @return void
     */
    public function testDeleteFileAttachmentCollectionByCompanyIds(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentCollectionDeleteCriteriaTransfer = (new FileAttachmentCollectionDeleteCriteriaTransfer())
            ->addIdCompany($companyTransfer->getIdCompany());

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionResponseTransfer->getErrors());
        $this->assertSame(0, $this->tester->countFileAttachmentsByIdFile($fileTransfer->getIdFile()));
    }

    /**
     * @return void
     */
    public function testDeleteFileAttachmentCollectionByCompanyUserIds(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyUserTransfer->getIdCompanyUser(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER,
        ]);

        $fileAttachmentCollectionDeleteCriteriaTransfer = (new FileAttachmentCollectionDeleteCriteriaTransfer())
            ->addIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionResponseTransfer->getErrors());
        $this->assertSame(0, $this->tester->countFileAttachmentsByIdFile($fileTransfer->getIdFile()));
    }

    /**
     * @return void
     */
    public function testDeleteFileAttachmentCollectionByCompanyBusinessUnitIds(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
        ]);

        $fileAttachmentCollectionDeleteCriteriaTransfer = (new FileAttachmentCollectionDeleteCriteriaTransfer())
            ->addIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionResponseTransfer->getErrors());
        $this->assertSame(0, $this->tester->countFileAttachmentsByIdFile($fileTransfer->getIdFile()));
    }

    /**
     * @return void
     */
    public function testDeleteFileAttachmentCollectionByCompanyIdRemovesOnlySpecifiedAttachment(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyTransferTwo = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransfer->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyTransferTwo->getIdCompany(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        ]);

        $fileAttachmentCollectionDeleteCriteriaTransfer = (new FileAttachmentCollectionDeleteCriteriaTransfer())
            ->addIdCompany($companyTransfer->getIdCompany());

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->countFileAttachmentsByIdFile($fileTransfer->getIdFile()));
    }

    /**
     * @return void
     */
    public function testDeleteFileAttachmentCollectionByCompanyUserIdRemovesOnlySpecifiedAttachment(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $customerTransferTwo = $this->tester->haveCustomer();
        $companyUserTransferTwo = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $customerTransferTwo->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $customerTransferTwo,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyUserTransfer->getIdCompanyUser(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER,
        ]);

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyUserTransferTwo->getIdCompanyUser(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER,
        ]);

        $fileAttachmentCollectionDeleteCriteriaTransfer = (new FileAttachmentCollectionDeleteCriteriaTransfer())
            ->addIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->countFileAttachmentsByIdFile($fileTransfer->getIdFile()));
    }

    /**
     * @return void
     */
    public function testDeleteFileAttachmentCollectionByCompanyBusinessUnitIdRemovesOnlySpecifiedAttachment(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyBusinessUnitTransferTwo = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $fileTransfer = $this->tester->haveFile();

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
        ]);

        $this->tester->haveFileAttachment([
            FileAttachmentTransfer::FILE => $fileTransfer,
            FileAttachmentTransfer::ENTITY_ID => $companyBusinessUnitTransferTwo->getIdCompanyBusinessUnit(),
            FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
        ]);

        $fileAttachmentCollectionDeleteCriteriaTransfer = (new FileAttachmentCollectionDeleteCriteriaTransfer())
            ->addIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        // Act
        $fileAttachmentCollectionResponseTransfer = $this->tester->getFacade()->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $fileAttachmentCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->countFileAttachmentsByIdFile($fileTransfer->getIdFile()));
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlockDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\CmsSlotBlockDataImport\Communication\Plugin\CmsSlotBlockDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotBlockDataImport
 * @group Communication
 * @group Plugin
 * @group CmsSlotBlockDataImportPluginTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockDataImportPluginTest extends Unit
{
    protected const CMS_SLOT_CONTENT_PROVIDER_TYPE = 'SprykerCmsSlotBlock';
    protected const CMS_SLOT_TEMPLATE_PATH = '@TestPage/views/pdp/pdp.twig';
    protected const CMS_SLOT_KEY = 'test-slot-key';
    protected const CMS_BLOCK_KEY = 'test-block-key';
    protected const CMS_PAGE_KEY = 'test-page-key';
    protected const CATEGORY_KEY = 'test-category-key';
    protected const PRODUCT_ABSTRACT_SKU = 'test-product-abstract-sku';

    /**
     * @var \SprykerTest\Zed\CmsSlotBlockDataImport\CmsSlotBlockDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureCmsSlotBlockTableIsEmpty();
        $this->tester->ensureCmsSlotTemplateTableIsEmpty();
        $this->tester->ensureCmsSlotTableIsEmpty();
        $this->tester->removeCmsBlockByKey(static::CMS_BLOCK_KEY);
    }

    /**
     * @return void
     */
    public function testCmsSlotBlockDataImportThrowsExceptionWhenSlotTemplateDoesNotExist(): void
    {
        // Arrange
        $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::KEY => static::CMS_SLOT_KEY,
            CmsSlotTransfer::CONTENT_PROVIDER_TYPE => static::CMS_SLOT_CONTENT_PROVIDER_TYPE,
        ]);
        $this->tester->haveCmsBlock([CmsBlockTransfer::KEY => static::CMS_BLOCK_KEY]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(codecept_data_dir() . 'import/cms_slot_block.csv');

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find CMS slot template ID by path "' . static::CMS_SLOT_TEMPLATE_PATH . '"');

        // Act
        (new CmsSlotBlockDataImportPlugin())->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testCmsSlotBlockDataImportThrowsExceptionWhenSlotDoesNotExist(): void
    {
        // Arrange
        $this->tester->haveCmsSlotTemplateInDb([CmsSlotTemplateTransfer::PATH => static::CMS_SLOT_TEMPLATE_PATH]);
        $this->tester->haveCmsBlock([CmsBlockTransfer::KEY => static::CMS_BLOCK_KEY]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(codecept_data_dir() . 'import/cms_slot_block.csv');

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find CMS slot ID by key "' . static::CMS_SLOT_KEY . '"');

        // Act
        (new CmsSlotBlockDataImportPlugin())->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testCmsSlotBlockDataImportThrowsExceptionWhenBlockDoesNotExist(): void
    {
        // Arrange
        $this->tester->haveCmsSlotTemplateInDb([CmsSlotTemplateTransfer::PATH => static::CMS_SLOT_TEMPLATE_PATH]);
        $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::KEY => static::CMS_SLOT_KEY,
            CmsSlotTransfer::CONTENT_PROVIDER_TYPE => static::CMS_SLOT_CONTENT_PROVIDER_TYPE,
        ]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(codecept_data_dir() . 'import/cms_slot_block.csv');

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find CMS Block ID by key "' . static::CMS_BLOCK_KEY . '"');

        // Act
        (new CmsSlotBlockDataImportPlugin())->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testCmsSlotBlockDataImportPopulatesTableWhenDataIsCorrect(): void
    {
        // Arrange
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplateInDb([CmsSlotTemplateTransfer::PATH => static::CMS_SLOT_TEMPLATE_PATH]);
        $cmsSlotTransfer = $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::KEY => static::CMS_SLOT_KEY,
            CmsSlotTransfer::CONTENT_PROVIDER_TYPE => static::CMS_SLOT_CONTENT_PROVIDER_TYPE,
        ]);
        $cmsBlockTransfer = $this->tester->haveCmsBlock([CmsBlockTransfer::KEY => static::CMS_BLOCK_KEY]);
        $categoryTransfer = $this->tester->haveCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_SKU]);
        $cmsSlotBlockConditions = $this->tester->getCmsSlotBlockConditions($categoryTransfer, $productAbstractTransfer);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(codecept_data_dir() . 'import/cms_slot_block.csv');

        // Act
        $dataImporterReportTransfer = (new CmsSlotBlockDataImportPlugin())->import($dataImportConfigurationTransfer);

        $cmsSlotBlockTransferFromDb = $this->tester->findCmsSlotBlock(
            $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            $cmsSlotTransfer->getIdCmsSlot()
        );

        //Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertEquals($cmsSlotBlockTransferFromDb->getIdCmsBlock(), $cmsBlockTransfer->getIdCmsBlock());
        $this->assertEquals($cmsSlotBlockTransferFromDb->getPosition(), 1);
        $this->assertEquals($cmsSlotBlockConditions, $cmsSlotBlockTransferFromDb->getConditions());
    }

    /**
     * @return void
     */
    public function testCmsSlotBlockDataImportPopulatesTableWithEmptyConditionsWhenThereAreNoConditionsInCsv(): void
    {
        // Arrange
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplateInDb([CmsSlotTemplateTransfer::PATH => static::CMS_SLOT_TEMPLATE_PATH]);
        $cmsSlotTransfer = $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::KEY => static::CMS_SLOT_KEY,
            CmsSlotTransfer::CONTENT_PROVIDER_TYPE => static::CMS_SLOT_CONTENT_PROVIDER_TYPE,
        ]);
        $this->tester->haveCmsBlock([CmsBlockTransfer::KEY => static::CMS_BLOCK_KEY]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(codecept_data_dir() . 'import/cms_slot_block_without_conditions.csv');

        // Act
        (new CmsSlotBlockDataImportPlugin())->import($dataImportConfigurationTransfer);

        $cmsSlotBlockTransferFromDb = $this->tester->findCmsSlotBlock(
            $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            $cmsSlotTransfer->getIdCmsSlot()
        );

        //Assert
        $this->assertEquals([], $cmsSlotBlockTransferFromDb->getConditions());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotBlock
 * @group Business
 * @group Facade
 * @group CmsSlotBlockFacadeTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsSlotBlock\CmsSlotBlockBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepository
     */
    protected $cmsSlotBlockRepository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cmsSlotBlockRepository = new CmsSlotBlockRepository();

        $this->tester->ensureCmsSlotBlockTableIsEmpty();
        $this->tester->ensureCmsSlotTableIsEmpty();
        $this->tester->ensureCmsSlotTemplateTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testSaveCmsSlotBlockRelationsAddsNewRelations(): void
    {
        // Arrange
        $cmsBlockTransfer1 = $this->tester->haveCmsBlock();
        $cmsBlockTransfer2 = $this->tester->haveCmsBlock();
        $cmsSlotTransfer1 = $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::KEY => 'slt-1',
        ]);
        $cmsSlotTransfer2 = $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::KEY => 'slt-2',
        ]);
        $cmsSlotTemplateTransfer1 = $this->tester->haveCmsSlotTemplateInDb([
            CmsSlotTemplateTransfer::PATH => 'path1',
        ]);
        $cmsSlotTemplateTransfer2 = $this->tester->haveCmsSlotTemplateInDb([
            CmsSlotTemplateTransfer::PATH => 'path2',
        ]);

        $cmsSlotBlockCollectionTransfer = new CmsSlotBlockCollectionTransfer();
        $cmsSlotBlockTransfer1_1 = (new CmsSlotBlockTransfer())->setIdCmsBlock($cmsBlockTransfer1->getIdCmsBlock())
            ->setIdSlotTemplate($cmsSlotTemplateTransfer1->getIdCmsSlotTemplate())
            ->setIdSlot($cmsSlotTransfer1->getIdCmsSlot())
            ->setPosition(1)
            ->setConditions([]);
        $cmsSlotBlockTransfer1_2 = (new CmsSlotBlockTransfer())->setIdCmsBlock($cmsBlockTransfer2->getIdCmsBlock())
            ->setIdSlotTemplate($cmsSlotTemplateTransfer1->getIdCmsSlotTemplate())
            ->setIdSlot($cmsSlotTransfer1->getIdCmsSlot())
            ->setPosition(2)
            ->setConditions([]);
        $cmsSlotBlockTransfer2 = (new CmsSlotBlockTransfer())->setIdCmsBlock($cmsBlockTransfer2->getIdCmsBlock())
            ->setIdSlotTemplate($cmsSlotTemplateTransfer2->getIdCmsSlotTemplate())
            ->setIdSlot($cmsSlotTransfer2->getIdCmsSlot())
            ->setPosition(1)
            ->setConditions([]);
        $cmsSlotBlockCollectionTransfer->addCmsSlotBlock($cmsSlotBlockTransfer1_1);
        $cmsSlotBlockCollectionTransfer->addCmsSlotBlock($cmsSlotBlockTransfer1_2);
        $cmsSlotBlockCollectionTransfer->addCmsSlotBlock($cmsSlotBlockTransfer2);

        // Act
        $this->tester->createCmsSlotBlockFacade()->saveCmsSlotBlockRelations($cmsSlotBlockCollectionTransfer);

        $cmsSlotBlocks1 = $this->cmsSlotBlockRepository->getCmsSlotBlocks(
            $cmsSlotTemplateTransfer1->getIdCmsSlotTemplate(),
            $cmsSlotTransfer1->getIdCmsSlot()
        )->getCmsSlotBlocks();
        $cmsSlotBlockTransferFromDb1_1 = $cmsSlotBlocks1[0];
        $cmsSlotBlockTransferFromDb1_2 = $cmsSlotBlocks1[1];
        $cmsSlotBlocks2 = $this->cmsSlotBlockRepository->getCmsSlotBlocks(
            $cmsSlotTemplateTransfer2->getIdCmsSlotTemplate(),
            $cmsSlotTransfer2->getIdCmsSlot()
        )->getCmsSlotBlocks();
        $cmsSlotBlockTransferFromDb2 = $cmsSlotBlocks2[0];

        // Assert
        $this->assertCount(2, $cmsSlotBlocks1);
        $this->assertCount(1, $cmsSlotBlocks2);
        $this->assertEquals($cmsSlotBlockTransfer1_1, $cmsSlotBlockTransferFromDb1_1);
        $this->assertEquals($cmsSlotBlockTransfer1_2, $cmsSlotBlockTransferFromDb1_2);
        $this->assertEquals($cmsSlotBlockTransfer2, $cmsSlotBlockTransferFromDb2);
    }

    /**
     * @return void
     */
    public function testSaveCmsSlotBlockRelationsUpdatesRelations(): void
    {
        // Arrange
        $cmsBlockTransfer1 = $this->tester->haveCmsBlock();
        $cmsBlockTransfer2 = $this->tester->haveCmsBlock();
        $cmsSlotTransfer = $this->tester->haveCmsSlotInDb();
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplateInDb();

        $this->tester->haveCmsSlotBlockInDb([
            CmsSlotBlockTransfer::ID_SLOT_TEMPLATE => $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            CmsSlotBlockTransfer::ID_SLOT => $cmsSlotTransfer->getIdCmsSlot(),
            CmsSlotBlockTransfer::ID_CMS_BLOCK => $cmsBlockTransfer1->getIdCmsBlock(),
        ]);
        $this->tester->haveCmsSlotBlockInDb([
            CmsSlotBlockTransfer::ID_SLOT_TEMPLATE => $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            CmsSlotBlockTransfer::ID_SLOT => $cmsSlotTransfer->getIdCmsSlot(),
            CmsSlotBlockTransfer::ID_CMS_BLOCK => $cmsBlockTransfer2->getIdCmsBlock(),
        ]);

        $cmsSlotBlockCollectionTransfer = new CmsSlotBlockCollectionTransfer();
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->setIdCmsBlock($cmsBlockTransfer1->getIdCmsBlock())
            ->setIdSlotTemplate($cmsSlotTemplateTransfer->getIdCmsSlotTemplate())
            ->setIdSlot($cmsSlotTransfer->getIdCmsSlot())
            ->setPosition(1)
            ->setConditions([]);
        $cmsSlotBlockCollectionTransfer->addCmsSlotBlock($cmsSlotBlockTransfer);

        // Act
        $this->tester->createCmsSlotBlockFacade()->saveCmsSlotBlockRelations($cmsSlotBlockCollectionTransfer);
        $cmsSlotBlocks = $this->cmsSlotBlockRepository->getCmsSlotBlocks(
            $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            $cmsSlotTransfer->getIdCmsSlot()
        )->getCmsSlotBlocks();

        // Assert
        $this->assertCount(1, $cmsSlotBlocks);
        $this->assertEquals($cmsSlotBlockTransfer, $cmsSlotBlocks[0]);
    }

    /**
     * @return void
     */
    public function testGetCmsSlotBlockCollectionReturnsCorrectData(): void
    {
        // Arrange
        $cmsBlockTransfer1 = $this->tester->haveCmsBlock();
        $cmsBlockTransfer2 = $this->tester->haveCmsBlock();
        $cmsSlotTransfer = $this->tester->haveCmsSlotInDb();
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplateInDb();

        $cmsSlotBlockTransfer1 = $this->tester->haveCmsSlotBlockInDb([
            CmsSlotBlockTransfer::ID_SLOT_TEMPLATE => $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            CmsSlotBlockTransfer::ID_SLOT => $cmsSlotTransfer->getIdCmsSlot(),
            CmsSlotBlockTransfer::ID_CMS_BLOCK => $cmsBlockTransfer1->getIdCmsBlock(),
        ]);
        $cmsSlotBlockTransfer2 = $this->tester->haveCmsSlotBlockInDb([
            CmsSlotBlockTransfer::ID_SLOT_TEMPLATE => $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            CmsSlotBlockTransfer::ID_SLOT => $cmsSlotTransfer->getIdCmsSlot(),
            CmsSlotBlockTransfer::ID_CMS_BLOCK => $cmsBlockTransfer2->getIdCmsBlock(),
        ]);

        // Act
        $cmsSlotBlocks = $this->tester->createCmsSlotBlockFacade()->getCmsSlotBlockCollection(
            $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            $cmsSlotTransfer->getIdCmsSlot()
        )->getCmsSlotBlocks();

        // Assert
        $this->assertCount(2, $cmsSlotBlocks);
        $this->assertEquals($cmsSlotBlockTransfer1, $cmsSlotBlocks[0]);
        $this->assertEquals($cmsSlotBlockTransfer2, $cmsSlotBlocks[1]);
    }

    /**
     * @return void
     */
    public function testGetCmsSlotBlockCollectionReturnsEmptyCollectionIfRelationsDoNotExist(): void
    {
        // Act
        $cmsSlotBlocks = $this->tester->createCmsSlotBlockFacade()
            ->getCmsSlotBlockCollection(1, 1)
            ->getCmsSlotBlocks();

        // Assert
        $this->assertCount(0, $cmsSlotBlocks);
    }

    /**
     * @return void
     */
    public function testGetCmsBlocksWithSlotRelationsReturnsDataWithCorrectLimit(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);

        $filterTransfer = (new FilterTransfer())->setLimit(2);

        // Act
        $cmsBlockTransfers = $this->tester->createCmsSlotBlockFacade()->getCmsBlocksWithSlotRelations($filterTransfer);

        // Assert
        $this->assertCount(2, $cmsBlockTransfers);
    }

    /**
     * @return void
     */
    public function testGetCmsBlocksWithSlotRelationsReturnsDataWithCorrectOffset(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $countCmsBlocks = SpyCmsBlockQuery::create()->count();
        $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $filterTransfer = (new FilterTransfer())->setOffset($countCmsBlocks);

        // Act
        $cmsBlockTransfers = $this->tester->createCmsSlotBlockFacade()->getCmsBlocksWithSlotRelations($filterTransfer);

        // Assert
        $this->assertCount(1, $cmsBlockTransfers);
    }

    /**
     * @return void
     */
    public function testGetCmsBlocksWithSlotRelationsReturnsDataWithOrderAsc(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $cmsBlockTransfer = $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $filterTransfer = (new FilterTransfer())
            ->setOrderBy(SpyCmsBlockTableMap::COL_ID_CMS_BLOCK)
            ->setOrderDirection('ASC');

        // Act
        $cmsBlockTransfers = $this->tester->createCmsSlotBlockFacade()->getCmsBlocksWithSlotRelations($filterTransfer);

        // Assert
        $this->assertEquals($cmsBlockTransfer->getIdCmsBlock(), $cmsBlockTransfers[count($cmsBlockTransfers) - 1]->getIdCmsBlock());
    }

    /**
     * @return void
     */
    public function testGetCmsBlocksWithSlotRelationsReturnsDataWithOrderDesc(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $cmsBlockTransfer = $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $filterTransfer = (new FilterTransfer())
            ->setOrderBy(SpyCmsBlockTableMap::COL_ID_CMS_BLOCK)
            ->setOrderDirection('DESC');

        // Act
        $cmsBlockTransfers = $this->tester->createCmsSlotBlockFacade()->getCmsBlocksWithSlotRelations($filterTransfer);

        // Assert
        $this->assertEquals($cmsBlockTransfer->getIdCmsBlock(), $cmsBlockTransfers[0]->getIdCmsBlock());
    }
}

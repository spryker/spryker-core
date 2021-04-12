<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockCategoryConnector\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockCategoryConnector
 * @group Business
 * @group Facade
 * @group CmsBlockCategoryConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CmsBlockCategoryConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateCmsBlockCategoryRelations(): void
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $this->assertEmpty($cmsBlockTransfer->getIdCategories());

        $this->tester->getCmsBlockCategoryConnectorFacade()
            ->syncCmsBlockCategoryPosition();
        $cmsBlockCategoryPositionTransfer = $this->tester->getCmsBlockCategoryConnectorFacade()
            ->findCmsBlockCategoryPositionByName($this->tester->getDefaultPositionName());

        $categoryTransfer = $this->tester->haveCategory();
        $cmsBlockTransfer->setFkTemplate($categoryTransfer->getFkCategoryTemplate());
        $cmsBlockTransfer->setIdCategories([
            $cmsBlockCategoryPositionTransfer->getIdCmsBlockCategoryPosition() => [$categoryTransfer->getIdCategory()],
        ]);

        $this->tester->getCmsBlockCategoryConnectorFacade()
            ->updateCmsBlockCategoryRelations($cmsBlockTransfer);

        $cmsBlockTransfer = $this->tester->getCmsBlockCategoryConnectorFacade()
            ->hydrateCmsBlockCategoryRelations($cmsBlockTransfer);

        $this->assertEquals([$categoryTransfer->getIdCategory()], $cmsBlockTransfer->getIdCategories());
    }

    /**
     * @return void
     */
    public function testHydrateCmsBlockCategoryRelations(): void
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $this->assertEmpty($cmsBlockTransfer->getIdCategories());

        $this->tester->getCmsBlockCategoryConnectorFacade()
            ->syncCmsBlockCategoryPosition();

        $cmsBlockCategoryPositionTransfer = $this->tester->getCmsBlockCategoryConnectorFacade()
            ->findCmsBlockCategoryPositionByName($this->tester->getDefaultPositionName());

        $categoryTransfer = $this->tester->haveCategory();
        $cmsBlockTransfer->setFkTemplate($categoryTransfer->getFkCategoryTemplate());
        $cmsBlockTransfer->setIdCategories([
            $cmsBlockCategoryPositionTransfer->getIdCmsBlockCategoryPosition() => [$categoryTransfer->getIdCategory()],
        ]);

        $this->tester->getCmsBlockCategoryConnectorFacade()
            ->updateCmsBlockCategoryRelations($cmsBlockTransfer);

        $cmsBlockTransfer = $this->tester->getCmsBlockCategoryConnectorFacade()
            ->hydrateCmsBlockCategoryRelations($cmsBlockTransfer);

        $this->assertEquals([$categoryTransfer->getIdCategory()], $cmsBlockTransfer->getIdCategories());
    }

    /**
     * @return void
     */
    public function testSyncCmsBlockCategoryPosition(): void
    {
        $this->tester->getCmsBlockCategoryConnectorFacade()
            ->syncCmsBlockCategoryPosition();

        $cmsBlockCategoryPositionTransfer = $this->tester->getCmsBlockCategoryConnectorFacade()
            ->findCmsBlockCategoryPositionByName($this->tester->getDefaultPositionName());

        $this->assertNotEmpty($cmsBlockCategoryPositionTransfer);
    }

    /**
     * @return void
     */
    public function testGetCmsBlockIdsWithNamesByCategoryWillReturnCmsBlockIdsWithNames(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $cmsBlockTransfer = $this->tester->haveCmsBlockWithCategory($categoryTransfer);

        $expectedCmsBlockIdsWithNames = [
            $cmsBlockTransfer->getIdCmsBlock() => $cmsBlockTransfer->getName(),
        ];

        // Act
        $cmsBlockNames = $this->tester
            ->getCmsBlockCategoryConnectorFacade()
            ->getCmsBlockIdsWithNamesByCategory($categoryTransfer);

        // Assert
        $this->assertSame($expectedCmsBlockIdsWithNames, $cmsBlockNames, 'Cms blocks should be filtered by category.');
    }
}

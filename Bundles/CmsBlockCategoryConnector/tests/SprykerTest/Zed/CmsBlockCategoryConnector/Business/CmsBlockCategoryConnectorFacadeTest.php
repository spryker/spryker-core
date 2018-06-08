<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockCategoryConnector\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;

/**
 * Auto-generated group annotations
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
     * @var \SprykerTest\Zed\CmsBlockCategoryConnector\BusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateCmsBlockCategoryRelations()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $this->assertEmpty($cmsBlockTransfer->getIdCategories());

        $this->createCmsBlockCategoryConnectorFacade()
            ->syncCmsBlockCategoryPosition();
        $cmsBlockCategoryPositionTransfer = $this->createCmsBlockCategoryConnectorFacade()
            ->findCmsBlockCategoryPositionByName($this->getDefaultPositionName());

        $categoryTransfer = $this->tester->haveCategory();
        $cmsBlockTransfer->setFkTemplate($categoryTransfer->getFkCategoryTemplate());
        $cmsBlockTransfer->setIdCategories([
            $cmsBlockCategoryPositionTransfer->getIdCmsBlockCategoryPosition() => [$categoryTransfer->getIdCategory()],
        ]);

        $this->createCmsBlockCategoryConnectorFacade()
            ->updateCmsBlockCategoryRelations($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockCategoryConnectorFacade()
            ->hydrateCmsBlockCategoryRelations($cmsBlockTransfer);

        $this->assertEquals([$categoryTransfer->getIdCategory()], $cmsBlockTransfer->getIdCategories());
    }

    /**
     * @return void
     */
    public function testHydrateCmsBlockCategoryRelations()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $this->assertEmpty($cmsBlockTransfer->getIdCategories());

        $this->createCmsBlockCategoryConnectorFacade()
            ->syncCmsBlockCategoryPosition();

        $cmsBlockCategoryPositionTransfer = $this->createCmsBlockCategoryConnectorFacade()
            ->findCmsBlockCategoryPositionByName($this->getDefaultPositionName());

        $categoryTransfer = $this->tester->haveCategory();
        $cmsBlockTransfer->setFkTemplate($categoryTransfer->getFkCategoryTemplate());
        $cmsBlockTransfer->setIdCategories([
            $cmsBlockCategoryPositionTransfer->getIdCmsBlockCategoryPosition() => [$categoryTransfer->getIdCategory()],
        ]);

        $this->createCmsBlockCategoryConnectorFacade()
            ->updateCmsBlockCategoryRelations($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockCategoryConnectorFacade()
            ->hydrateCmsBlockCategoryRelations($cmsBlockTransfer);

        $this->assertEquals([$categoryTransfer->getIdCategory()], $cmsBlockTransfer->getIdCategories());
    }

    /**
     * @return void
     */
    public function testSyncCmsBlockCategoryPosition()
    {
        $this->createCmsBlockCategoryConnectorFacade()
            ->syncCmsBlockCategoryPosition();

        $cmsBlockCategoryPositionTransfer = $this->createCmsBlockCategoryConnectorFacade()
            ->findCmsBlockCategoryPositionByName($this->getDefaultPositionName());

        $this->assertNotEmpty($cmsBlockCategoryPositionTransfer);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface
     */
    protected function createCmsBlockCategoryConnectorFacade()
    {
        return $this->tester->getLocator()->cmsBlockCategoryConnector()->facade();
    }

    /**
     * @return string
     */
    protected function getDefaultPositionName()
    {
        return (new CmsBlockCategoryConnectorConfig())->getCmsBlockCategoryPositionDefault();
    }
}

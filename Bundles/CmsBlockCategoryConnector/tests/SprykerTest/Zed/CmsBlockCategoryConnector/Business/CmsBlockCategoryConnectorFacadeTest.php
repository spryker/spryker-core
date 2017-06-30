<?php

namespace SprykerTest\Zed\CmsBlockCategoryConnector\Business;

use Codeception\TestCase\Test;

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
class CmsBlockCategoryConnectorFacadeTest extends Test
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

        $categoryTransfer = $this->tester->haveCategory();
        $cmsBlockTransfer->setIdCategories([$categoryTransfer->getIdCategory()]);

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

        $categoryTransfer = $this->tester->haveCategory();
        $cmsBlockTransfer->setIdCategories([$categoryTransfer->getIdCategory()]);

        $this->createCmsBlockCategoryConnectorFacade()
            ->updateCmsBlockCategoryRelations($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockCategoryConnectorFacade()
            ->hydrateCmsBlockCategoryRelations($cmsBlockTransfer);

        $this->assertEquals([$categoryTransfer->getIdCategory()], $cmsBlockTransfer->getIdCategories());
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface
     */
    protected function createCmsBlockCategoryConnectorFacade()
    {
        return $this->tester->getLocator()->cmsBlockCategoryConnector()->facade();
    }

}

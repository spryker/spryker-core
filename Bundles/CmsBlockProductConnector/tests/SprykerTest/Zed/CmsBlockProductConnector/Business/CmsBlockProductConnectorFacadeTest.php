<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockProductConnector\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockProductConnector
 * @group Business
 * @group Facade
 * @group CmsBlockProductConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CmsBlockProductConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsBlockProductConnector\BusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateCmsBlockProductRelations()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $this->assertEmpty($cmsBlockTransfer->getIdProductAbstracts());

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $cmsBlockTransfer->setIdProductAbstracts([$productAbstractTransfer->getIdProductAbstract()]);

        $this->createCmsBlockProductConnectorFacade()
            ->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockProductConnectorFacade()
            ->hydrateCmsBlockProductRelations($cmsBlockTransfer);

        $this->assertEquals([$productAbstractTransfer->getIdProductAbstract()], $cmsBlockTransfer->getIdProductAbstracts());
    }

    /**
     * @return void
     */
    public function testHydrateCmsBlockProductRelations()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $this->assertEmpty($cmsBlockTransfer->getIdProductAbstracts());

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $cmsBlockTransfer->setIdProductAbstracts([$productAbstractTransfer->getIdProductAbstract()]);

        $this->createCmsBlockProductConnectorFacade()
            ->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockProductConnectorFacade()
            ->hydrateCmsBlockProductRelations($cmsBlockTransfer);

        $this->assertEquals([$productAbstractTransfer->getIdProductAbstract()], $cmsBlockTransfer->getIdProductAbstracts());
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface
     */
    protected function createCmsBlockProductConnectorFacade()
    {
        return $this->tester->getLocator()->cmsBlockProductConnector()->facade();
    }
}

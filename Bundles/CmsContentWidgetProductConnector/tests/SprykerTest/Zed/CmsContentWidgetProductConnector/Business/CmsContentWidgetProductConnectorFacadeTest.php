<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsContentWidgetProductConnector\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CmsContentWidgetProductConnector\Business\CmsContentWidgetProductConnectorFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsContentWidgetProductConnector
 * @group Business
 * @group Facade
 * @group CmsContentWidgetProductConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CmsContentWidgetProductConnectorFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testMapProductSkuListShouldMapSkuToPrimaryKey()
    {
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $cmsProductConnectorFacade = new CmsContentWidgetProductConnectorFacade();
        $map = $cmsProductConnectorFacade->mapProductSkuList([
            $productAbstractTransfer1->getSku(),
            $productAbstractTransfer2->getSku(),
        ]);

        $this->assertArrayHasKey($productAbstractTransfer1->getSku(), $map);
        $this->assertEquals($productAbstractTransfer1->getIdProductAbstract(), $map[$productAbstractTransfer1->getSku()]);

        $this->assertArrayHasKey($productAbstractTransfer2->getSku(), $map);
        $this->assertEquals($productAbstractTransfer2->getIdProductAbstract(), $map[$productAbstractTransfer2->getSku()]);
    }
}

<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsProductConnector\Business;

use Codeception\TestCase\Test;
use Spryker\Zed\CmsProductConnector\Business\CmsProductConnectorFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsProductConnector
 * @group Business
 * @group Facade
 * @group CmsProductConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CmsProductConnectorFacadeTest extends Test
{

    /**
     * @return void
     */
    public function testMapProductSkuListShouldMapSkuToPrimaryKey()
    {
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $cmsProductConnectorFacade = new CmsProductConnectorFacade();
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsContentWidgetProductSetConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Zed\CmsContentWidgetProductSetConnector\Business\CmsContentWidgetProductSetConnectorFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsContentWidgetProductSetConnector
 * @group Business
 * @group Facade
 * @group CmsContentWidgetProductSetConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CmsContentWidgetProductSetConnectorFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testMapProductKeyListShouldMapSetKeyToPrimaryKey()
    {
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $productSetTransfer = $this->tester->haveProductSet([
            ProductSetTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ]);

        $cmsProductSetConnectorFacade = $this->createCmsProductSetConnectorFacade();
        $mappedProductSets = $cmsProductSetConnectorFacade->mapProductKeyList([$productSetTransfer->getProductSetKey()]);

        $this->assertCount(1, $mappedProductSets);
        $this->assertArrayHasKey($productSetTransfer->getProductSetKey(), $mappedProductSets);
        $this->assertEquals(
            $productSetTransfer->getIdProductSet(),
            $mappedProductSets[$productSetTransfer->getProductSetKey()]
        );
    }

    /**
     * @return \Spryker\Zed\CmsContentWidgetProductSetConnector\Business\CmsContentWidgetProductSetConnectorFacade
     */
    protected function createCmsProductSetConnectorFacade()
    {
        return new CmsContentWidgetProductSetConnectorFacade();
    }
}

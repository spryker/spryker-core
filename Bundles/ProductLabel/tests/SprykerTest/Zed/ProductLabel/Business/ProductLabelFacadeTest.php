<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ProductLabelBuilder;

class ProductLabelFacadeTest extends Test
{

    /**
     * @var \SprykerTest\Zed\ProductLabel\BusinessTester
     */
    protected $tester;

//    public function testReadLabelReturnsTransfer()
//    {
//        $productLabelFacade = $this->createProductLabelFacade();
//        $productLabelTransfer = $productLabelFacade->readLabel(1);
//        $this->assertInstanceOf('\Generated\Shared\Transfer\ProductLabelTransfer', $productLabelTransfer);
//    }

    public function testCreateLabelPersistsData()
    {
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelTransfer = (new ProductLabelBuilder())->build();
        $productLabelFacade->createLabel($productLabelTransfer);
        $persistedProductLabelTransfer = $productLabelFacade->readLabel($productLabelTransfer->getIdProductLabel());
        $this->assertSame($productLabelTransfer, $persistedProductLabelTransfer);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function createProductLabelFacade()
    {
        return $this->tester->getLocator()->productLabel()->facade();
    }

}

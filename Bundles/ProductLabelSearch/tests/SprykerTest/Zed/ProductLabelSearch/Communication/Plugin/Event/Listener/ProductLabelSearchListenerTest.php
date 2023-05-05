<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacade;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;
use Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductLabelSearchListenerTest
 * Add your own group annotations below this line
 */
class ProductLabelSearchListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductLabelSearch\ProductLabelSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testQueryProductLabelByProductLabelIds(): void
    {
        $productLabelTransfer = $this->tester->haveProductLabel();
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productAbstractTransfer->getIdProductAbstract(),
        );

        $productLabelSearchQueryContainer = new ProductLabelSearchQueryContainer();
        $result = $productLabelSearchQueryContainer
            ->queryProductLabelByProductLabelIds([$productLabelTransfer->getIdProductLabel()])->count();

        $this->assertSame(1, $result);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function createProductLabelFacade(): ProductLabelFacadeInterface
    {
        return new ProductLabelFacade();
    }
}

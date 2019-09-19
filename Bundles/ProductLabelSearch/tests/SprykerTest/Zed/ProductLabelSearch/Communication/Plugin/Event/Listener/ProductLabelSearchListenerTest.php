<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacade;
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
     * @return void
     */
    public function testQueryProductLabelByProductLabelIds()
    {
        $productLabelSearchQueryContainer = new ProductLabelSearchQueryContainer();
        $labelId = $this->createProductLabelFacade()->findLabelByLabelName('Standard label')->getIdProductLabel();
        $result = $productLabelSearchQueryContainer->queryProductLabelByProductLabelIds([$labelId])->count();

        $this->assertSame(48, $result);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function createProductLabelFacade()
    {
        return new ProductLabelFacade();
    }
}

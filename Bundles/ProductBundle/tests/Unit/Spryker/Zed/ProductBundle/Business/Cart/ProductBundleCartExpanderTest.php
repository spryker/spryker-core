<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductBundle\Business\Cart;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Cart
 * @group ProductBundleCartExpanderTest
 */
class ProductBundleCartExpanderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testExpandBundleItems()
    {

    }

    protected function createProductExpanderMock()
    {
        return $this->getMockBuilder(ProductBundleCartExpander::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductBundleQueryContainerInterface
     */
    protected function createProductBundleQueryContainer()
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }
}

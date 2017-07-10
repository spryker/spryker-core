<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute\Business;

use Codeception\TestCase\Test;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductAttribute
 * @group Business
 * @group Facade
 * @group ProductAttributeFacadeTest
 * Add your own group annotations below this line
 */
class ProductAttributeFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;


    /**
     * @var \SprykerTest\Zed\ProductAttribute\BusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productAttributeFacade = new ProductAttributeFacade();
        $this->productAttributeFacade->setFactory($this->getBusinessFactory());
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory
     */
    protected function getBusinessFactory()
    {
        $customerBusinessFactory = new ProductAttributeBusinessFactory();
        $customerBusinessFactory->setContainer($this->getContainer());

        return $customerBusinessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        $dependencyProvider = new ProductAttributeDependencyProvider();
        $container = new Container();

        $dependencyProvider->provideBusinessLayerDependencies($container);

        $container[ProductAttributeDependencyProvider::FACADE_LOCALE] = $this->getMockBuilder(ProductAttributeToLocaleInterface::class)->getMock();

        return $container;
    }

    public function testGetProductAbstractAttributeValues()
    {
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        print_r($productAbstractTransfer->toArray());die;
    }
}

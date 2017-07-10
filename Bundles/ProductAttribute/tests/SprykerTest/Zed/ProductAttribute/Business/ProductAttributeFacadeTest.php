<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
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

    const ATTRIBUTES = 'attributes';
    const LOCALIZED_ATTRIBUTES = 'localizedAttributes';

    const DATA_PRODUCT_ATTRIBUTES = [
        'foo' => 'Foo Value',
        'bar' => '20 units',
    ];

    const DATA_PRODUCT_LOCALIZED_ATTRIBUTES = [
        46 => [
            'foo' => 'Foo Value DE',
        ],
        66 => [
            'foo' => 'Foo Value US',
        ],
    ];

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

    /**
     * @return void
     */
    public function testGetProductAbstractAttributeValues()
    {
        $this->markTestSkipped();

        $productAbstractTransfer = $this->generateProductAbstractTransfer();

        $productAttributes = $this->productAttributeFacade->getProductAbstractAttributeValues($productAbstractTransfer->getIdProductAbstract());

        print_r($productAttributes);
        print_r($productAbstractTransfer->toArray());
        die;
    }

    /**
     * @return array
     */
    protected function generateLocalizedAttributes()
    {
        $results = [];
        foreach (static::DATA_PRODUCT_LOCALIZED_ATTRIBUTES as $idLocale => $localizedData) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setIdLocale($idLocale);

            $localizedAttributeTransfer = new LocalizedAttributesTransfer();
            $localizedAttributeTransfer->setAttributes($localizedData);
            $localizedAttributeTransfer->setLocale($localeTransfer);

            $results[] = $localizedAttributeTransfer;
        }

        return $results;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function generateProductAbstractTransfer()
    {
        $localizedAttributes = $this->generateLocalizedAttributes();

        $productAbstractTransfer = $this->tester->haveProductAbstract([
            static::ATTRIBUTES => static::DATA_PRODUCT_ATTRIBUTES,
        ]);

        $productAbstractTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        return $productAbstractTransfer;
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Product\Business\Attribute;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoder;
use Spryker\Zed\Product\Business\Attribute\AttributeLoader;
use Spryker\Zed\Product\Business\Attribute\AttributeMerger;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Product
 * @group Business
 * @group Attribute
 * @group ProductConcreteAttributeMergeTest
 */
class ProductConcreteAttributeMergeTest extends Test
{

    const ID_LOCALE = 1;

    /**
     * @return array
     */
    public function getCombinedConcreteAttributesDataProvider()
    {
        return [
            'concrete product without attributes' => $this->getProductEntityWithoutAttributesData(),
            'concrete product with concrete attributes' => $this->getProductEntityWithConcreteAttributesData(),
            'concrete product with localized concrete attributes' => $this->getProductWithLocalizedConcreteAttributesData(),
            'concrete product with abstract attributes' => $this->getProductWithAbstractAttributesData(),
            'concrete product with localized abstract attributes' => $this->getProductWithLocalizedAbstractAttributesData(),
        ];
    }

    /**
     * @dataProvider getCombinedConcreteAttributesDataProvider
     *
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param array $expectedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function testGetCombinedConcreteAttributesReturnsCorrectAttributeMergeResults(SpyProduct $productEntity, array $expectedAttributes, LocaleTransfer $localeTransfer = null)
    {
        /** @var \Spryker\Zed\Product\Business\Attribute\AttributeLoader|\PHPUnit_Framework_MockObject_MockObject $attributeManger */
        $attributeManger = $this->getMockBuilder(AttributeLoader::class)
            ->setConstructorArgs([
                $this->getMock(ProductQueryContainer::class),
                new AttributeMerger(),
                new AttributeEncoder(),
            ])
            ->setMethods(['getProductEntity'])
            ->getMock();

        $attributeManger
            ->method('getProductEntity')
            ->willReturn($productEntity);

        $actualAttributes = $attributeManger->getCombinedConcreteAttributes(new ProductConcreteTransfer(), $localeTransfer);

        $this->assertEquals($expectedAttributes, $actualAttributes);
    }

    /**
     * @return array
     */
    protected function getProductEntityWithoutAttributesData()
    {
        $expectedAttributes = [];

        return [$this->createProductEntity(), $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getProductEntityWithConcreteAttributesData()
    {
        $expectedAttributes = [
            'foo' => 'Foo', // from product concrete
        ];

        $productEntity = $this->createProductEntity();
        $productEntity->setAttributes('{"foo":"Foo"}');

        return [$productEntity, $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getProductWithLocalizedConcreteAttributesData()
    {
        $expectedAttributes = [
            'foo' => 'Foo - localized', // from localized product concrete
            'bar' => 'Bar', // from product concrete
        ];

        $localeTransfer = $this->createLocaleTransfer();

        $productEntity = $this->createProductEntity();
        $productEntity
            ->setAttributes('{"foo":"Foo","bar":"Bar"}')
            ->addSpyProductLocalizedAttributes(
                (new SpyProductLocalizedAttributes())
                    ->setFkLocale($localeTransfer->getIdLocale())
                    ->setAttributes('{"foo":"Foo - localized"}')
            );

        return [$productEntity, $expectedAttributes, $localeTransfer];
    }

    /**
     * @return array
     */
    protected function getProductWithAbstractAttributesData()
    {
        $expectedAttributes = [
            'foo' => 'Foo - concrete', // from product concrete
            'bar' => 'Bar', // from product concrete
            'baz' => 'Baz', // from product abstract
        ];

        $productEntity = $this->createProductEntity();
        $productEntity->setAttributes('{"foo":"Foo - concrete","bar":"Bar"}');
        $productEntity->getSpyProductAbstract()->setAttributes('{"foo":"Foo","baz":"Baz"}');

        return [$productEntity, $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getProductWithLocalizedAbstractAttributesData()
    {
        $expectedAttributes = [
            'foo' => 'Foo - localized', // from localized product concrete
            'bar' => 'Bar', // from product concrete
            'baz' => 'Baz - localized', // from localized product abstract
            'waz' => 'Waz', // from product abstract
        ];

        $localeTransfer = $this->createLocaleTransfer();

        $productEntity = $this->createProductEntity();
        $productEntity
            ->setAttributes('{"foo":"Foo","bar":"Bar"}')
            ->addSpyProductLocalizedAttributes(
                (new SpyProductLocalizedAttributes())
                    ->setFkLocale($localeTransfer->getIdLocale())
                    ->setAttributes('{"foo":"Foo - localized"}')
            );

        $productEntity->getSpyProductAbstract()
            ->setAttributes('{"foo":"Foo - abstract","waz":"Waz"}')
            ->addSpyProductAbstractLocalizedAttributes(
                (new SpyProductAbstractLocalizedAttributes())
                    ->setFkLocale($localeTransfer->getIdLocale())
                    ->setAttributes('{"baz":"Baz - localized"}')
            );

        return [$productEntity, $expectedAttributes, $localeTransfer];
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function createProductEntity()
    {
        $productEntity = new SpyProduct();
        $productEntity->setSpyProductAbstract(new SpyProductAbstract());

        return $productEntity;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransfer()
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setIdLocale(self::ID_LOCALE);

        return $localeTransfer;
    }

}

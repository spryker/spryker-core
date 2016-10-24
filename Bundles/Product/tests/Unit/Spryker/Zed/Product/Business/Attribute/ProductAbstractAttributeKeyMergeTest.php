<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Product\Business\Attribute;

use Codeception\TestCase\Test;
use Functional\Spryker\Zed\ProductOption\Mock\ProductQueryContainer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoder;
use Spryker\Zed\Product\Business\Attribute\AttributeLoader;
use Spryker\Zed\Product\Business\Attribute\AttributeMerger;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Product
 * @group Business
 * @group Attribute
 * @group ProductAbstractAttributeKeyMergeTest
 */
class ProductAbstractAttributeKeyMergeTest extends Test
{

    const ID_LOCALE = 1;

    /**
     * @return array
     */
    public function getCombinedConcreteAttributesDataProvider()
    {
        return [
            'abstract product without attributes' => $this->getProductEntityWithoutAttributesData(),
            'abstract product with concrete attributes' => $this->getAbstractProductEntityWithAbstractAttributesData(),
            'abstract product with localized concrete attributes' => $this->getAbstractProductWithLocalizedAbstractAttributesData(),
            'abstract product with abstract attributes' => $this->getAbstractProductWithConcreteAttributesData(),
            'abstract product with localized abstract attributes' => $this->getAbstractProductWithLocalizedConcreteAttributesData(),
        ];
    }

    /**
     * @dataProvider getCombinedConcreteAttributesDataProvider
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param array $expectedAttributeKeys
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function testGetCombinedAbstractAttributeKeysReturnsCorrectAttributeMergeResults(
        SpyProductAbstract $productAbstractEntity,
        array $expectedAttributeKeys,
        LocaleTransfer $localeTransfer = null
    ) {
        /** @var \Spryker\Zed\Product\Business\Attribute\AttributeLoader|\PHPUnit_Framework_MockObject_MockObject $attributeManger */
        $attributeManger = $this->getMockBuilder(AttributeLoader::class)
            ->setConstructorArgs([
                $this->getMock(ProductQueryContainer::class),
                new AttributeMerger(),
                new AttributeEncoder()
            ])
            ->setMethods(['getProductAbstractEntity'])
            ->getMock();

        $attributeManger
            ->method('getProductAbstractEntity')
            ->willReturn($productAbstractEntity);

        $actualAttributeKeys = $attributeManger->getCombinedAbstractAttributeKeys(new ProductAbstractTransfer(), $localeTransfer);

        $this->assertEquals($expectedAttributeKeys, $actualAttributeKeys);
    }

    /**
     * @return array
     */
    protected function getProductEntityWithoutAttributesData()
    {
        $expectedAttributes = [];

        return [$this->createProductAbstractEntity(), $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getAbstractProductEntityWithAbstractAttributesData()
    {
        $expectedAttributes = [
            'foo', // from product abstract
        ];

        $productAbstractEntity = $this->createProductAbstractEntity();
        $productAbstractEntity->setAttributes('{"foo":"Foo"}');

        return [$productAbstractEntity, $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getAbstractProductWithLocalizedAbstractAttributesData()
    {
        $expectedAttributes = [
            'foo', // from localized product abstract
            'bar', // from product abstract
        ];

        $localeTransfer = $this->createLocaleTransfer();

        $productAbstractEntity = $this->createProductAbstractEntity();
        $productAbstractEntity
            ->setAttributes('{"foo":"Foo","bar":"Bar"}')
            ->addSpyProductAbstractLocalizedAttributes(
                (new SpyProductAbstractLocalizedAttributes())
                    ->setFkLocale($localeTransfer->getIdLocale())
                    ->setAttributes('{"foo":"Foo - localized"}')
            );

        return [$productAbstractEntity, $expectedAttributes, $localeTransfer];
    }

    /**
     * @return array
     */
    protected function getAbstractProductWithConcreteAttributesData()
    {
        $expectedAttributes = [
            'foo',
            'bar',
            'baz',
        ];

        $productAbstractEntity = $this->createProductAbstractEntity();
        $productAbstractEntity->setAttributes('{"foo":"Foo","bar":"Bar"}');

        $productAbstractEntity
            ->addSpyProduct(
                (new SpyProduct)->setAttributes('{"foo":"Foo 1","baz":"Baz 1"}')
            )
            ->addSpyProduct(
                (new SpyProduct)->setAttributes('{"foo":"Foo 2","baz":"Baz 2"}')
            )
            ->addSpyProduct(
                (new SpyProduct)->setAttributes('{"foo":"Foo 3","baz":"Baz 3"}')
            );

        return [$productAbstractEntity, $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getAbstractProductWithLocalizedConcreteAttributesData()
    {
        $expectedAttributes = [
            'foo',
            'bar',
            'baz',
            'waz',
        ];

        $localeTransfer = $this->createLocaleTransfer();

        $productAbstractEntity = $this->createProductAbstractEntity();
        $productAbstractEntity
            ->setAttributes('{"foo":"Foo","bar":"Bar"}')
            ->addSpyProductAbstractLocalizedAttributes(
                (new SpyProductAbstractLocalizedAttributes())
                    ->setFkLocale($localeTransfer->getIdLocale())
                    ->setAttributes('{"foo":"Foo - localized"}')
            );

        $productAbstractEntity
            ->addSpyProduct(
                (new SpyProduct)
                    ->setAttributes('{"foo":"Foo 1","baz":"Baz 1"}')
                    ->addSpyProductLocalizedAttributes(
                        (new SpyProductLocalizedAttributes())
                            ->setFkLocale($localeTransfer->getIdLocale())
                            ->setAttributes('{"foo":"Foo 1 - localized","baz":"Baz 1 - localized","waz":"Waz - localized"}')
                    )
                    ->addSpyProductLocalizedAttributes(
                        (new SpyProductLocalizedAttributes())
                            ->setFkLocale(999)
                            ->setAttributes('{"other-locale":"Other locale"}')
                    )
            )
            ->addSpyProduct(
                (new SpyProduct)
                    ->setAttributes('{"foo":"Foo 2","baz":"Baz 2"}')
                    ->addSpyProductLocalizedAttributes(
                        (new SpyProductLocalizedAttributes())
                            ->setFkLocale($localeTransfer->getIdLocale())
                            ->setAttributes('{"foo":"Foo 2 - localized","baz":"Baz 2 - localized"}')
                    )
            );

        return [$productAbstractEntity, $expectedAttributes, $localeTransfer];
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createProductAbstractEntity()
    {
        return new SpyProductAbstract();
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

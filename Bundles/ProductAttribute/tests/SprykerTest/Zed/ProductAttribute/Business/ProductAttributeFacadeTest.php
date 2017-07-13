<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation;
use Spryker\Shared\ProductAttribute\Code\KeyBuilder\AttributeGlossaryKeyBuilder;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeTranslator;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryBridge;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleBridge;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductBridge;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer;
use SprykerTest\Zed\ProductAttribute\ProductAttributeBusinessTester;

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
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade
     */
    protected $productAttributeFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer
     */
    protected $productAttributeQueryContainer;

    /**
     * @var \SprykerTest\Zed\ProductAttribute\ProductAttributeBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productAttributeFacade = new ProductAttributeFacade();
        $this->productAttributeQueryContainer = new ProductAttributeQueryContainer();

        $this->productFacade = new ProductAttributeToProductBridge(new ProductFacade());

        $this->tester->setProductFacade($this->productFacade);
        $this->tester->setProductAttributeFacade($this->productAttributeFacade);
    }

    /**
     * @return void
     */
    public function testCreateProductManagementAttributeCreatesNewAttributeEntity()
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setKey('foo')
            ->setInputType('bar');

        $productManagementAttributeTransfer = $this->productAttributeFacade->createProductManagementAttribute(
            $productManagementAttributeTransfer
        );

        $this->assertNotNull($productManagementAttributeTransfer->getIdProductManagementAttribute());
    }

    /**
     * @return void
     */
    public function testCreateProductManagementAttributeCreatesNewAttributeValueEntities()
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setKey('foo')
            ->setInputType('bar')
            ->setValues(new ArrayObject([
                (new ProductManagementAttributeValueTransfer())->setValue('a'),
                (new ProductManagementAttributeValueTransfer())->setValue('b'),
                (new ProductManagementAttributeValueTransfer())->setValue('c'),
            ]));

        $productManagementAttributeTransfer = $this->productAttributeFacade->createProductManagementAttribute(
            $productManagementAttributeTransfer
        );

        foreach ($productManagementAttributeTransfer->getValues() as $attributeValueTransfer) {
            $this->assertNotNull($attributeValueTransfer->getIdProductManagementAttributeValue());
        }
    }

    /**
     * @return void
     */
    public function testCreateProductManagementAttributeCreatesNewProductAttributeKey()
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setKey('foo')
            ->setInputType('bar');

        $productManagementAttributeTransfer = $this->productAttributeFacade->createProductManagementAttribute(
            $productManagementAttributeTransfer
        );

        $productAttributeKeyEntity = $this->productAttributeQueryContainer
            ->queryProductAttributeKey()
            ->findOneByKey($productManagementAttributeTransfer->getKey());

        $this->assertNotNull($productAttributeKeyEntity);
    }

    /**
     * @return void
     */
    public function testCreateProductManagementAttributeUsesExistingProductAttributeKey()
    {
        $productAttributeKeyEntity = new SpyProductAttributeKey();
        $productAttributeKeyEntity->setKey('some_unique_key_that_should_not_exist_in_db');
        $productAttributeKeyEntity->save();

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setKey($productAttributeKeyEntity->getKey())
            ->setInputType('bar');

        $productManagementAttributeTransfer = $this->productAttributeFacade->createProductManagementAttribute(
            $productManagementAttributeTransfer
        );

        $this->assertNotNull($productManagementAttributeTransfer->getIdProductManagementAttribute());
    }

    /**
     * @return void
     */
    public function testUpdateProductManagementAttributeUpdatesAttributeEntity()
    {
        $productAttributeKeyEntity = $this->tester->createProductManagementAttributeEntity();

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute())
            ->setKey($productAttributeKeyEntity->getSpyProductAttributeKey()->getKey())
            ->setInputType('baz');

        $actualProductManagementAttributeTransfer = $this->productAttributeFacade->updateProductManagementAttribute(
            $productManagementAttributeTransfer
        );

        $this->assertEquals($productManagementAttributeTransfer, $actualProductManagementAttributeTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateProductManagementAttributeUpdatesExistingAttributeValueEntities()
    {
        $productAttributeKeyEntity = $this->tester->createProductManagementAttributeEntity(['a', 'b', 'c']);

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute())
            ->setKey('foo')
            ->setInputType('bar');

        $updatedValues = ['a', 'b', 'd'];
        foreach ($updatedValues as $updatedValue) {
            $productManagementAttributeTransfer->addValue(
                (new ProductManagementAttributeValueTransfer())->setValue($updatedValue)
            );
        }

        $productManagementAttributeTransfer = $this->productAttributeFacade->updateProductManagementAttribute(
            $productManagementAttributeTransfer
        );

        foreach ($productManagementAttributeTransfer->getValues() as $attributeValueTransfer) {
            $this->assertContains($attributeValueTransfer->getValue(), $updatedValues);
        }
    }

    /**
     * @return void
     */
    public function testTranslateProductManagementAttributeKeyInGlossary()
    {
        $productManagementBusinessFactoryMock = $this->getMockBuilder(ProductAttributeBusinessFactory::class)
            ->setMethods(['createAttributeTranslator'])
            ->getMock();

        $productManagementBusinessFactoryMock->method('createAttributeTranslator')
            ->willReturn($this->getAttributeTranslationMock());

        /** @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory $productManagementBusinessFactoryMock */
        $this->productAttributeFacade->setFactory($productManagementBusinessFactoryMock);

        $productAttributeKeyEntity = $this->tester->createProductManagementAttributeEntity();

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute())
            ->setLocalizedKeys(new ArrayObject([
                (new LocalizedProductManagementAttributeKeyTransfer())
                    ->setLocaleName('aa_AA')
                    ->setKeyTranslation('Foo'),
                (new LocalizedProductManagementAttributeKeyTransfer())
                    ->setLocaleName('bb_BB')
                    ->setKeyTranslation('Bar'),
            ]));

        $this->productAttributeFacade->translateProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * @return void
     */
    public function testTranslateProductManagementAttributeValues()
    {
        $productManagementBusinessFactoryMock = $this->getMockBuilder(ProductAttributeBusinessFactory::class)
            ->setMethods(['createAttributeTranslator'])
            ->getMock();

        $productManagementBusinessFactoryMock->method('createAttributeTranslator')
            ->willReturn($this->getAttributeTranslationMock());

        /** @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory $productManagementBusinessFactoryMock */
        $this->productAttributeFacade->setFactory($productManagementBusinessFactoryMock);

        $productAttributeKeyEntity = $this->tester->createProductManagementAttributeEntity(['a', 'b', 'c']);

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute());

        foreach ($productAttributeKeyEntity->getSpyProductManagementAttributeValues() as $productManagementAttributeValueEntity) {
            $productManagementAttributeValueTransfer = (new ProductManagementAttributeValueTransfer())
                ->fromArray($productManagementAttributeValueEntity->toArray(), true);

            $productManagementAttributeValueTransfer
                ->addLocalizedValue(
                    (new ProductManagementAttributeValueTranslationTransfer())
                        ->setFkLocale($this->tester->getLocale('aa_AA')->getIdLocale())
                        ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to a language')
                )
                ->addLocalizedValue(
                    (new ProductManagementAttributeValueTranslationTransfer())
                        ->setFkLocale($this->tester->getLocale('bb_BB')->getIdLocale())
                        ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to another language')
                );

            $productManagementAttributeTransfer->addValue($productManagementAttributeValueTransfer);
        }

        $this->productAttributeFacade->translateProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributeReturnsNullIfEntityDoesNotExist()
    {
        $this->assertNull($this->productAttributeFacade->getProductManagementAttribute(0));
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributeReturnsFullyHydratedTransfer()
    {
        $productAttributeKeyEntity = $this->tester->createProductManagementAttributeEntity(['a', 'b', 'c']);

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute());

        foreach ($productAttributeKeyEntity->getSpyProductManagementAttributeValues() as $productManagementAttributeValueEntity) {
            $attributeValueTranslationEntity = new SpyProductManagementAttributeValueTranslation();
            $attributeValueTranslationEntity
                ->setFkProductManagementAttributeValue($productManagementAttributeValueEntity->getIdProductManagementAttributeValue())
                ->setFkLocale($this->tester->getLocale('aa_AA')->getIdLocale())
                ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to a language')
                ->save();

            $attributeValueTranslationEntity = new SpyProductManagementAttributeValueTranslation();
            $attributeValueTranslationEntity
                ->setFkProductManagementAttributeValue($productManagementAttributeValueEntity->getIdProductManagementAttributeValue())
                ->setFkLocale($this->tester->getLocale('bb_BB')->getIdLocale())
                ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to another language')
                ->save();
        }

        $productManagementAttributeTransfer = $this->productAttributeFacade->getProductManagementAttribute(
            $productManagementAttributeTransfer->getIdProductManagementAttribute()
        );

        $this->assertNotNull($productManagementAttributeTransfer);
        $this->assertCount(3, $productManagementAttributeTransfer->getValues());
        $this->assertCount(2, $productManagementAttributeTransfer->getValues()[0]->getLocalizedValues());
    }

    /**
     * @return void
     */
    public function testGetProductAbstractAttributeValues()
    {
        $productAbstractTransfer = $this->tester->createSampleAbstractProduct(ProductAttributeBusinessTester::ABSTRACT_SKU);

        $productAttributesValues = $this->productAttributeFacade->getProductAbstractAttributeValues(
            $productAbstractTransfer->getIdProductAbstract()
        );

        $this->assertSame(ProductAttributeBusinessTester::PRODUCT_ATTRIBUTE_VALUES, $productAttributesValues);
    }

    /**
     * @return void
     */
    public function testGetProductAttributeValues()
    {
        $productAbstractTransfer = $this->tester->createSampleAbstractProduct(ProductAttributeBusinessTester::ABSTRACT_SKU);
        $productTransfer = $this->tester->createSampleProduct($productAbstractTransfer, ProductAttributeBusinessTester::CONCRETE_SKU);

        $productValues = $this->productAttributeFacade->getProductAttributeValues(
            $productTransfer->getIdProductConcrete()
        );

        $this->assertSame(ProductAttributeBusinessTester::PRODUCT_ATTRIBUTE_VALUES, $productValues);
    }

    /**
     * @return void
     */
    public function testGetMetaAttributesForProductAbstractShouldReturnEmptySetForUndefinedAttributes()
    {
        $productAbstractTransfer = $this->tester->createSampleAbstractProduct(ProductAttributeBusinessTester::ABSTRACT_SKU);

        $metaAttributes = $this->productAttributeFacade->getMetaAttributesForProductAbstract(
            $productAbstractTransfer->getIdProductAbstract()
        );

        $this->assertEmpty($metaAttributes);
    }

    /**
     * @return void
     */
    public function testGetMetaAttributesForProductShouldReturnEmptySetForUndefinedAttributes()
    {
        $productAbstractTransfer = $this->tester->createSampleAbstractProduct(ProductAttributeBusinessTester::ABSTRACT_SKU);
        $productTransfer = $this->tester->createSampleProduct($productAbstractTransfer, ProductAttributeBusinessTester::CONCRETE_SKU);

        $metaAttributes = $this->productAttributeFacade->getMetaAttributesForProduct(
            $productTransfer->getIdProductConcrete()
        );

        $this->assertEmpty($metaAttributes);
    }

    /**
     * @return void
     */
    public function testGetMetaAttributesForProductAbstract()
    {
        $data = $this->tester->createSampleAttributeMetadataWithSuperAttributeData();
        $productAbstractTransfer = $this->tester->createSampleAbstractProduct(ProductAttributeBusinessTester::ABSTRACT_SKU, $data);

        $metaAttributes = $this->productAttributeFacade->getMetaAttributesForProductAbstract(
            $productAbstractTransfer->getIdProductAbstract()
        );

        $this->assertNotEmpty($metaAttributes);
        $this->assertArrayHasKey(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY, $metaAttributes);
        $this->assertArrayHasKey(ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY, $metaAttributes);
    }

    /**
     * @return void
     */
    public function testGetMetaAttributesForProduct()
    {
        $data = $this->tester->createSampleAttributeMetadataWithSuperAttributeData();
        $productAbstractTransfer = $this->tester->createSampleAbstractProduct(ProductAttributeBusinessTester::ABSTRACT_SKU);
        $productTransfer = $this->tester->createSampleProduct($productAbstractTransfer, ProductAttributeBusinessTester::CONCRETE_SKU, $data);

        $metaAttributes = $this->productAttributeFacade->getMetaAttributesForProduct(
            $productTransfer->getIdProductConcrete()
        );

        $this->assertNotEmpty($metaAttributes);
        $this->assertArrayHasKey(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY, $metaAttributes);
        $this->assertArrayHasKey(ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY, $metaAttributes);
    }

    /**
     * @return void
     */
    public function testSaveAbstractAttributes()
    {
        $productAbstractTransfer = $this->tester->createSampleAbstractProduct(ProductAttributeBusinessTester::ABSTRACT_SKU);
        $fooMetaAttributeTransfer = $this->tester->createSampleAttributeMetadata(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY, false);

        $attributesToSave = [
            [
                'key' => $fooMetaAttributeTransfer->getKey(),
                'id' => $fooMetaAttributeTransfer->getIdProductManagementAttribute(),
                'locale_code' => '_',
                'value' => 'New Foo Value',

            ],
            [
                'key' => 'undefined__key',
                'id' => null,
                'locale_code' => 46,
                'value' => 'xxx',
            ],
        ];

        $this->productAttributeFacade->saveAbstractAttributes(
            $productAbstractTransfer->getIdProductAbstract(),
            $attributesToSave
        );

        $productAttributesValues = $this->productAttributeFacade->getProductAbstractAttributeValues(
            $productAbstractTransfer->getIdProductAbstract()
        );

        $this->assertSame([
            '_' => [
                'foo' => 'New Foo Value',
            ],
            46 => [
                'undefined__key' => 'xxx',
            ],
            66 => [],
        ], $productAttributesValues);
    }

    /**
     * @return void
     */
    public function testSaveConcreteAttributes()
    {
        $productAbstractTransfer = $this->tester->createSampleAbstractProduct(ProductAttributeBusinessTester::ABSTRACT_SKU);
        $productTransfer = $this->tester->createSampleProduct($productAbstractTransfer, ProductAttributeBusinessTester::CONCRETE_SKU);
        $fooMetaAttributeTransfer = $this->tester->createSampleAttributeMetadata(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY, false);

        $attributesToSave = [
            [
                'key' => $fooMetaAttributeTransfer->getKey(),
                'id' => $fooMetaAttributeTransfer->getIdProductManagementAttribute(),
                'locale_code' => '_',
                'value' => 'New Foo Value',
            ],
            [
                'key' => $fooMetaAttributeTransfer->getKey(),
                'id' => $fooMetaAttributeTransfer->getIdProductManagementAttribute(),
                'locale_code' => 46,
                'value' => '',
            ],
            [
                'key' => $fooMetaAttributeTransfer->getKey(),
                'id' => $fooMetaAttributeTransfer->getIdProductManagementAttribute(),
                'locale_code' => 66,
                'value' => '',
            ],
            [
                'key' => 'undefined__key',
                'id' => null,
                'locale_code' => 46,
                'value' => 'xxx',
            ],
        ];

        $this->productAttributeFacade->saveConcreteAttributes(
            $productTransfer->getIdProductConcrete(),
            $attributesToSave
        );

        $productAttributesValues = $this->productAttributeFacade->getProductAttributeValues(
            $productTransfer->getIdProductConcrete()
        );

        $this->assertSame([
            '_' => [
                'foo' => 'New Foo Value',
            ],
            46 => [
                'undefined__key' => 'xxx',
            ],
            66 => [],
        ], $productAttributesValues);
    }

    /**
     * @return void
     */
    public function testSuggestKeys()
    {
        $this->tester->createSampleAttributeMetadata(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY, false);
        $this->tester->createSampleAttributeMetadata(ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY, true);

        $suggestedKeys = $this->productAttributeFacade->suggestKeys(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY);

        $this->assertNotEmpty($suggestedKeys);
    }

    /**
     * @return void
     */
    public function testSuggestKeysShouldIgnoreSuperAttributes()
    {
        $this->tester->createSampleAttributeMetadata(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY, false);
        $this->tester->createSampleAttributeMetadata(ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY, true);

        $suggestedKeys = $this->productAttributeFacade->suggestKeys(ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY);

        $this->assertEmpty($suggestedKeys);
    }

    /**
     * @return void
     */
    public function testExtractKeysFromAttributes()
    {
        $keys = $this->productAttributeFacade->extractKeysFromAttributes(ProductAttributeBusinessTester::PRODUCT_ATTRIBUTE_VALUES);

        $this->assertSame(['foo', 'bar'], $keys);
    }

    /**
     * @return void
     */
    public function testExtractValuesFromAttributes()
    {
        $values = $this->productAttributeFacade->extractValuesFromAttributes(ProductAttributeBusinessTester::PRODUCT_ATTRIBUTE_VALUES);

        $this->assertSame([
            'Foo Value',
            '20 units',
            'Foo Value DE',
            'Foo Value US',
        ], $values);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAttributeTranslationMock()
    {
        $productManagementToLocaleBridgeMock = $this->getMockBuilder(ProductAttributeToLocaleBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productManagementToGlossaryBridgeMock = $this->getMockBuilder(ProductAttributeToGlossaryBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $glossaryKeyBuilderMock = $this->getMockBuilder(AttributeGlossaryKeyBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $attributeTranslatorMock = $this->getMockBuilder(AttributeTranslator::class)->setConstructorArgs([
                $this->productAttributeQueryContainer,
                $productManagementToLocaleBridgeMock,
                $productManagementToGlossaryBridgeMock,
                $glossaryKeyBuilderMock,
            ])->setMethods(['getLocaleByName'])->getMock();

        $attributeTranslatorMock->method('getLocaleByName')->willReturnCallback(function ($localeName) {
            return $this->tester->getLocale($localeName);
        });

        return $attributeTranslatorMock;
    }

}

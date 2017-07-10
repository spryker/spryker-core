<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductManagement\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation;
use Spryker\Shared\ProductManagement\Code\KeyBuilder\AttributeGlossaryKeyBuilder;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeTranslator;
use Spryker\Zed\ProductManagement\Business\ProductManagementBusinessFactory;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacade;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleBridge;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductManagement
 * @group Business
 * @group ProductManagementFacadeAttributeTest
 */
class ProductManagementFacadeAttributeTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductManagement\Business\ProductManagementFacade
     */
    protected $productManagementFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer
     */
    protected $productManagementQueryContainer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productManagementFacade = new ProductManagementFacade();
        $this->productManagementQueryContainer = new ProductManagementQueryContainer();
    }

    /**
     * @return void
     */
    public function testCreateProductManagementAttributeCreatesNewAttributeEntity()
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setKey('foo')
            ->setInputType('bar');

        $productManagementAttributeTransfer = $this->productManagementFacade
            ->createProductManagementAttribute($productManagementAttributeTransfer);

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

        $productManagementAttributeTransfer = $this->productManagementFacade
            ->createProductManagementAttribute($productManagementAttributeTransfer);

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

        $productManagementAttributeTransfer = $this->productManagementFacade
            ->createProductManagementAttribute($productManagementAttributeTransfer);

        $productAttributeKeyEntity = $this->productManagementQueryContainer
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

        $productManagementAttributeTransfer = $this->productManagementFacade
            ->createProductManagementAttribute($productManagementAttributeTransfer);

        $this->assertNotNull($productManagementAttributeTransfer->getIdProductManagementAttribute());
    }

    /**
     * @return void
     */
    public function testUpdateProductManagementAttributeUpdatesAttributeEntity()
    {
        $productAttributeKeyEntity = $this->createProductManagementAttributeEntity();

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute())
            ->setKey($productAttributeKeyEntity->getSpyProductAttributeKey()->getKey())
            ->setInputType('baz');

        $actualProductManagementAttributeTransfer = $this->productManagementFacade
            ->updateProductManagementAttribute($productManagementAttributeTransfer);

        $this->assertEquals($productManagementAttributeTransfer, $actualProductManagementAttributeTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateProductManagementAttributeUpdatesExistingAttributeValueEntities()
    {
        $productAttributeKeyEntity = $this->createProductManagementAttributeEntity(['a', 'b', 'c']);

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute())
            ->setKey('foo')
            ->setInputType('bar');

        $updatedValues = ['a', 'b', 'd'];
        foreach ($updatedValues as $updatedValue) {
            $productManagementAttributeTransfer
                ->addValue((new ProductManagementAttributeValueTransfer())->setValue($updatedValue));
        }

        $productManagementAttributeTransfer = $this->productManagementFacade
            ->updateProductManagementAttribute($productManagementAttributeTransfer);

        foreach ($productManagementAttributeTransfer->getValues() as $attributeValueTransfer) {
            $this->assertContains($attributeValueTransfer->getValue(), $updatedValues);
        }
    }

    /**
     * @return void
     */
    public function testTranslateProductManagementAttributeKeyInGlossary()
    {
        $productManagementBusinessFactoryMock = $this->getMockBuilder(ProductManagementBusinessFactory::class)
            ->setMethods(['createAttributeTranslator'])
            ->getMock();

        $productManagementBusinessFactoryMock
            ->method('createAttributeTranslator')
            ->willReturn($this->getAttributeTranslationMock());

        $this->productManagementFacade->setFactory($productManagementBusinessFactoryMock);

        $productAttributeKeyEntity = $this->createProductManagementAttributeEntity();

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

        $this->productManagementFacade->translateProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * @return void
     */
    public function testTranslateProductManagementAttributeValues()
    {
        $productManagementBusinessFactoryMock = $this->getMockBuilder(ProductManagementBusinessFactory::class)
            ->setMethods(['createAttributeTranslator'])
            ->getMock();

        $productManagementBusinessFactoryMock
            ->method('createAttributeTranslator')
            ->willReturn($this->getAttributeTranslationMock());

        $this->productManagementFacade->setFactory($productManagementBusinessFactoryMock);

        $productAttributeKeyEntity = $this->createProductManagementAttributeEntity(['a', 'b', 'c']);

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute());

        foreach ($productAttributeKeyEntity->getSpyProductManagementAttributeValues() as $productManagementAttributeValueEntity) {
            $productManagementAttributeValueTransfer = (new ProductManagementAttributeValueTransfer())
                ->fromArray($productManagementAttributeValueEntity->toArray(), true);

            $productManagementAttributeValueTransfer
                ->addLocalizedValue((new ProductManagementAttributeValueTranslationTransfer())
                    ->setFkLocale($this->getLocale('aa_AA')->getIdLocale())
                    ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to a language'))
                ->addLocalizedValue((new ProductManagementAttributeValueTranslationTransfer())
                    ->setFkLocale($this->getLocale('bb_BB')->getIdLocale())
                    ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to another language'));
            $productManagementAttributeTransfer->addValue($productManagementAttributeValueTransfer);
        }

        $this->productManagementFacade->translateProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributeReturnsNullIfEntityDoesNotExist()
    {
        $this->assertNull($this->productManagementFacade->getProductManagementAttribute(0));
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributeReturnsFullyHydratedTransfer()
    {
        $productAttributeKeyEntity = $this->createProductManagementAttributeEntity(['a', 'b', 'c']);

        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIdProductManagementAttribute($productAttributeKeyEntity->getIdProductManagementAttribute());

        foreach ($productAttributeKeyEntity->getSpyProductManagementAttributeValues() as $productManagementAttributeValueEntity) {
            $attributeValueTranslationEntity = new SpyProductManagementAttributeValueTranslation();
            $attributeValueTranslationEntity
                ->setFkProductManagementAttributeValue($productManagementAttributeValueEntity->getIdProductManagementAttributeValue())
                ->setFkLocale($this->getLocale('aa_AA')->getIdLocale())
                ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to a language')
                ->save();

            $attributeValueTranslationEntity = new SpyProductManagementAttributeValueTranslation();
            $attributeValueTranslationEntity
                ->setFkProductManagementAttributeValue($productManagementAttributeValueEntity->getIdProductManagementAttributeValue())
                ->setFkLocale($this->getLocale('bb_BB')->getIdLocale())
                ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to another language')
                ->save();
        }

        $productManagementAttributeTransfer = $this->productManagementFacade
            ->getProductManagementAttribute($productManagementAttributeTransfer->getIdProductManagementAttribute());

        $this->assertNotNull($productManagementAttributeTransfer);
        $this->assertCount(3, $productManagementAttributeTransfer->getValues());
        $this->assertCount(2, $productManagementAttributeTransfer->getValues()[0]->getLocalizedValues());
    }

    /**
     * @return void
     */
    public function testSuggestUnusedAttributeKeys()
    {
        $keys = [
            'some-unique-key-1',
            'some-unique-key-2',
            'some-unique-key-3',
            'other-unique-key-1',
            'other-unique-key-2',
            'other-unique-key-3',
        ];
        foreach ($keys as $key) {
            $productAttributeKeyEntity = new SpyProductAttributeKey();
            $productAttributeKeyEntity->setKey($key);
            $productAttributeKeyEntity->save();
        }

        $result = $this->productManagementFacade->suggestUnusedAttributeKeys('some-unique-key-', 5);

        $this->assertCount(3, $result);

        foreach ($result as $key) {
            $this->assertContains($key, $keys);
        }
    }

    /**
     * @param array $values
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute
     */
    protected function createProductManagementAttributeEntity(array $values = [])
    {
        $productAttributeKeyEntity = new SpyProductAttributeKey();
        $productAttributeKeyEntity->setKey('some_unique_key_that_should_not_exist_in_db');
        $productAttributeKeyEntity->save();

        $productManagementAttributeEntity = new SpyProductManagementAttribute();
        $productManagementAttributeEntity
            ->setFkProductAttributeKey($productAttributeKeyEntity->getIdProductAttributeKey())
            ->setInputType('bar');
        $productManagementAttributeEntity->save();

        if (!empty($values)) {
            foreach ($values as $value) {
                $productManagementAttributeValueEntity = new SpyProductManagementAttributeValue();
                $productManagementAttributeValueEntity
                    ->setFkProductManagementAttribute($productManagementAttributeEntity->getIdProductManagementAttribute())
                    ->setValue($value);
                $productManagementAttributeValueEntity->save();
            }
        }

        return $productManagementAttributeEntity;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAttributeTranslationMock()
    {
        $productManagementToLocaleBridgeMock = $this->getMockBuilder(ProductManagementToLocaleBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productManagementToGlossaryBridgeMock = $this->getMockBuilder(ProductManagementToGlossaryBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $glossaryKeyBuilderMock = $this->getMockBuilder(AttributeGlossaryKeyBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $attributeTranslatorMock = $this->getMockBuilder(AttributeTranslator::class)
            ->setConstructorArgs([
                new ProductManagementQueryContainer(),
                $productManagementToLocaleBridgeMock,
                $productManagementToGlossaryBridgeMock,
                $glossaryKeyBuilderMock,
            ])
            ->setMethods(['getLocaleByName'])
            ->getMock();

        $attributeTranslatorMock
            ->method('getLocaleByName')
            ->willReturnCallback(function ($localeName) {
                return $this->getLocale($localeName);
            });

        return $attributeTranslatorMock;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocale($localeName)
    {
        $localeEntity = SpyLocaleQuery::create()
            ->filterByLocaleName($localeName)
            ->findOneOrCreate();

        $localeEntity->save();

        $localeTransfer = (new LocaleTransfer())->fromArray($localeEntity->toArray(), true);

        return $localeTransfer;
    }

}

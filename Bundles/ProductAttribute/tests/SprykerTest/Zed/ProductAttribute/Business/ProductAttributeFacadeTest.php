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
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation;
use Spryker\Shared\ProductAttribute\Code\KeyBuilder\AttributeGlossaryKeyBuilder;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeTranslator;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryBridge;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleBridge;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer;

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
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade
     */
    protected $productAttributeFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer
     */
    protected $productAttributeQueryContainer;

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
        $this->productAttributeQueryContainer = new ProductAttributeQueryContainer();
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
        $productAttributeKeyEntity = $this->createProductManagementAttributeEntity();

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

        $productManagementBusinessFactoryMock
            ->method('createAttributeTranslator')
            ->willReturn($this->getAttributeTranslationMock());

        /** @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory $productManagementBusinessFactoryMock */
        $this->productAttributeFacade->setFactory($productManagementBusinessFactoryMock);

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

        $productManagementBusinessFactoryMock
            ->method('createAttributeTranslator')
            ->willReturn($this->getAttributeTranslationMock());

        /** @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory $productManagementBusinessFactoryMock */
        $this->productAttributeFacade->setFactory($productManagementBusinessFactoryMock);

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
        $this->markTestSkipped();

        $productAbstractTransfer = $this->generateProductAbstractTransfer();

        $productAttributes = $this->productAttributeFacade->getProductAbstractAttributeValues(
            $productAbstractTransfer->getIdProductAbstract()
        );

        print_r($productAttributes);
        print_r($productAbstractTransfer->toArray());
        die;
    }

    /**
     * TODO move to tester
     *
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
     * TODO move to tester
     *
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

        $attributeTranslatorMock = $this->getMockBuilder(AttributeTranslator::class)
            ->setConstructorArgs([
                $this->productAttributeQueryContainer,
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

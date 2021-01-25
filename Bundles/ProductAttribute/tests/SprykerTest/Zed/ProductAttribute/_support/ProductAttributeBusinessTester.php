<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductAttributeBusinessTester extends Actor
{
    use _generated\ProductAttributeBusinessTesterActions;

    public const ABSTRACT_SKU = 'testFooBarAbstract';
    public const CONCRETE_SKU = 'testFooBarConcrete';
    public const SUPER_ATTRIBUTE_KEY = 'super_attribute';
    public const SUPER_ATTRIBUTE_VALUE = 'very super attribute';
    public const FOO_ATTRIBUTE_KEY = 'foo';
    public const ANOTHER_SUPER_ATTRIBUTE_KEY = 'another_super_attribute';
    public const ANOTHER_SUPER_ATTRIBUTE_VALUE = 'another super attribute value';

    public const DATA_PRODUCT_ATTRIBUTES_VALUES = [
        'foo' => 'Foo Value',
        'bar' => '20 units',
    ];

    public const LOCALE_ONE_NAME = 'de_DE';
    public const LOCALE_TWO_NAME = 'en_US';

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransferOne;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransferTwo;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleOne(): LocaleTransfer
    {
        if ($this->localeTransferOne === null) {
            $this->localeTransferOne = $this->haveLocale(['locale_name' => static::LOCALE_ONE_NAME]);
        }

        return $this->localeTransferOne;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleTwo(): LocaleTransfer
    {
        if ($this->localeTransferTwo === null) {
            $this->localeTransferTwo = $this->haveLocale(['locale_name' => static::LOCALE_TWO_NAME]);
        }

        return $this->localeTransferTwo;
    }

    /**
     * @return array
     */
    public function getSampleLocalizedProductAttributeValues(): array
    {
        $localeTransfer = $this->getLocaleOne();
        $localeTransfer2 = $this->getLocaleTwo();

        $result = [
            '_' => [
                'foo' => 'Foo Value',
                'bar' => '20 units',
            ],
            $localeTransfer->getLocaleName() => [
                'foo' => 'Foo Value DE',
            ],
            $localeTransfer2->getLocaleName() => [
                'foo' => 'Foo Value US',
            ],
        ];

        ksort($result);

        return $result;
    }

    /**
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface $productFacade
     *
     * @return void
     */
    public function setProductFacade(ProductAttributeToProductInterface $productFacade): void
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     *
     * @return void
     */
    public function setProductAttributeFacade(ProductAttributeFacadeInterface $productAttributeFacade): void
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param array $values
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute
     */
    public function createProductManagementAttributeEntity(array $values = []): SpyProductManagementAttribute
    {
        $productManagementAttributeEntity = $this->haveProductManagementAttributeEntity();

        if (!$values) {
            return $productManagementAttributeEntity;
        }

        foreach ($values as $value) {
            $productManagementAttributeValueEntity = (new SpyProductManagementAttributeValue())
                ->setFkProductManagementAttribute($productManagementAttributeEntity->getIdProductManagementAttribute())
                ->setValue($value);

            $productManagementAttributeValueEntity->save();
        }

        return $productManagementAttributeEntity;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale(string $localeName): LocaleTransfer
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
    public function generateLocalizedAttributes(): array
    {
        $results = [];
        $data = $this->getSampleLocalizedProductAttributeValues();
        unset($data[ProductAttributeConfig::DEFAULT_LOCALE]);

        foreach ($data as $localeCode => $localizedData) {
            $localeTransfer = $this->getLocale($localeCode);

            $localizedAttributeTransfer = new LocalizedAttributesTransfer();
            $localizedAttributeTransfer->setAttributes($localizedData);
            $localizedAttributeTransfer->setLocale($localeTransfer);
            $localizedAttributeTransfer->setName('product-' . rand(1, 1000));

            $results[] = $localizedAttributeTransfer;
        }

        return $results;
    }

    /**
     * @param string $sku
     * @param array|null $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function createSampleAbstractProduct(string $sku, ?array $data = null): ProductAbstractTransfer
    {
        $data = (!is_array($data)) ? self::DATA_PRODUCT_ATTRIBUTES_VALUES : $data;

        $productAbstractTransfer = $this->haveProductAbstract([
            'attributes' => $data,
            'sku' => $sku,
        ]);

        $localizedAttributes = $this->generateLocalizedAttributes();
        $productAbstractTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        $idProductAbstract = $this->productFacade->saveProduct($productAbstractTransfer, []);
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param string $sku
     * @param array|null $data
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createSampleProduct(ProductAbstractTransfer $productAbstractTransfer, string $sku, ?array $data = null): ProductConcreteTransfer
    {
        $data = (!is_array($data)) ? self::DATA_PRODUCT_ATTRIBUTES_VALUES : $data;

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($sku);
        $productConcreteTransfer->setAttributes($data);

        $localizedAttributes = $this->generateLocalizedAttributes();
        $productConcreteTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        $this->productFacade->saveProduct($productAbstractTransfer, [$productConcreteTransfer]);

        return $productConcreteTransfer;
    }

    /**
     * @param string $key
     * @param bool $isSuper
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createSampleAttributeMetadata(string $key, bool $isSuper = false): ProductManagementAttributeTransfer
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIsSuper($isSuper)
            ->setKey($key)
            ->setInputType('text');

        return $this->productAttributeFacade->createProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * @return array
     */
    public function createSampleAttributeMetadataWithSuperAttributeData(): array
    {
        $this->createSampleAttributeMetadata(self::FOO_ATTRIBUTE_KEY, false);
        $this->createSampleAttributeMetadata(self::SUPER_ATTRIBUTE_KEY, true);

        $data = self::DATA_PRODUCT_ATTRIBUTES_VALUES;
        $data[self::SUPER_ATTRIBUTE_KEY] = self::SUPER_ATTRIBUTE_VALUE;

        return $data;
    }

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute $productManagementAttributeEntity
     *
     * @return void
     */
    public function addAttributeValueTranslations(SpyProductManagementAttribute $productManagementAttributeEntity): void
    {
        foreach ($productManagementAttributeEntity->getSpyProductManagementAttributeValues() as $productManagementAttributeValueEntity) {
            $attributeValueTranslationEntity = new SpyProductManagementAttributeValueTranslation();
            $attributeValueTranslationEntity
                ->setFkProductManagementAttributeValue($productManagementAttributeValueEntity->getIdProductManagementAttributeValue())
                ->setFkLocale($this->getLocale(static::LOCALE_ONE_NAME)->getIdLocale())
                ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to a language')
                ->save();

            $attributeValueTranslationEntity = new SpyProductManagementAttributeValueTranslation();
            $attributeValueTranslationEntity
                ->setFkProductManagementAttributeValue($productManagementAttributeValueEntity->getIdProductManagementAttributeValue())
                ->setFkLocale($this->getLocale(static::LOCALE_TWO_NAME)->getIdLocale())
                ->setTranslation($productManagementAttributeValueEntity->getValue() . ' translated to another language')
                ->save();
        }
    }
}

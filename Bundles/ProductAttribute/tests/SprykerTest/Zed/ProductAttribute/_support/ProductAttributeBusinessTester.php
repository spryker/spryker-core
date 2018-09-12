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
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductAttributeBusinessTester extends Actor
{
    use _generated\ProductAttributeBusinessTesterActions;

    const ABSTRACT_SKU = 'testFooBarAbstract';
    const CONCRETE_SKU = 'testFooBarConcrete';
    const SUPER_ATTRIBUTE_KEY = 'super_attribute';
    const SUPER_ATTRIBUTE_VALUE = 'very super attribute';
    const FOO_ATTRIBUTE_KEY = 'foo';

    const DATA_PRODUCT_ATTRIBUTES_VALUES = [
        'foo' => 'Foo Value',
        'bar' => '20 units',
    ];

    const LOCALE_ONE_NAME = 'de_DE';
    const LOCALE_TWO_NAME = 'en_US';

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
    public function getLocaleOne()
    {
        if ($this->localeTransferOne === null) {
            $this->localeTransferOne = $this->haveLocale(['locale_name' => static::LOCALE_ONE_NAME]);
        }

        return $this->localeTransferOne;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleTwo()
    {
        if ($this->localeTransferTwo === null) {
            $this->localeTransferTwo = $this->haveLocale(['locale_name' => static::LOCALE_TWO_NAME]);
        }

        return $this->localeTransferTwo;
    }

    /**
     * @return array
     */
    public function getSampleLocalizedProductAttributeValues()
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
    public function setProductFacade(ProductAttributeToProductInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     *
     * @return void
     */
    public function setProductAttributeFacade(ProductAttributeFacadeInterface $productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param array $values
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute
     */
    public function createProductManagementAttributeEntity(array $values = [])
    {
        $productManagementAttributeEntity = $this->haveProductManagementAttributeEntity();

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
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName)
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
    public function generateLocalizedAttributes()
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
     * @param null|array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function createSampleAbstractProduct($sku, $data = null)
    {
        $data = (!is_array($data)) ? ProductAttributeBusinessTester::DATA_PRODUCT_ATTRIBUTES_VALUES : $data;

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
     * @param null|array $data
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createSampleProduct(ProductAbstractTransfer $productAbstractTransfer, $sku, $data = null)
    {
        $data = (!is_array($data)) ? ProductAttributeBusinessTester::DATA_PRODUCT_ATTRIBUTES_VALUES : $data;

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
    public function createSampleAttributeMetadata($key, $isSuper = false)
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIsSuper($isSuper)
            ->setKey($key)
            ->setInputType('text');

        $this->productAttributeFacade->createProductManagementAttribute($productManagementAttributeTransfer);

        return $productManagementAttributeTransfer;
    }

    /**
     * @return array
     */
    public function createSampleAttributeMetadataWithSuperAttributeData()
    {
        $this->createSampleAttributeMetadata(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY, false);
        $this->createSampleAttributeMetadata(ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY, true);

        $data = ProductAttributeBusinessTester::DATA_PRODUCT_ATTRIBUTES_VALUES;
        $data[ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY] = ProductAttributeBusinessTester::SUPER_ATTRIBUTE_VALUE;

        return $data;
    }
}

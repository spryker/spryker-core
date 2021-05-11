<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\LocalizedProductManagementAttributeKeyBuilder;
use Generated\Shared\DataBuilder\ProductManagementAttributeBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductAttributeDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    protected const COLUMN_PRODUCT_ATTRIBUTE_KEY = 'key';
    protected const COLUMN_PRODUCT_MANAGEMENT_ATTRIBUTE_FK_PRODUCT_ATTRIBUTE_KEY = 'fk_product_attribute_key';

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function generateProductManagementAttributeTransfer(array $seedData = []): ProductManagementAttributeTransfer
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeBuilder($seedData))->build();

        return $productManagementAttributeTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer
     */
    public function generateLocalizedProductManagementAttributeKeyTransfer(array $seedData = []): LocalizedProductManagementAttributeKeyTransfer
    {
        $localizedProductManagementAttributeKeyTransfer = (new LocalizedProductManagementAttributeKeyBuilder($seedData))->build();

        return $localizedProductManagementAttributeKeyTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKey
     */
    public function haveProductAttributeKeyEntity(array $seedData = []): SpyProductAttributeKey
    {
        $seedData = $seedData + ['key' => md5(microtime())];

        $productAttributeKeyEntity = new SpyProductAttributeKey();

        if (isset($seedData[static::COLUMN_PRODUCT_ATTRIBUTE_KEY])) {
            $productAttributeKeyEntity = (new SpyProductAttributeKeyQuery())
                ->filterByKey($seedData[static::COLUMN_PRODUCT_ATTRIBUTE_KEY])
                ->findOneOrCreate();
        }

        $productAttributeKeyEntity->fromArray($seedData);

        if ($productAttributeKeyEntity->isNew()) {
            $this->getDataCleanupHelper()->_addCleanup(function () use ($productAttributeKeyEntity): void {
                $productAttributeKeyEntity->delete();
            });
        }

        $productAttributeKeyEntity->save();

        return $productAttributeKeyEntity;
    }

    /**
     * @param array $seedData
     * @param array $productAttributeKeySeed
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute
     */
    public function haveProductManagementAttributeEntity(array $seedData = [], array $productAttributeKeySeed = []): SpyProductManagementAttribute
    {
        $seedData = $seedData + [
            'input_type' => 'bar',
            static::COLUMN_PRODUCT_MANAGEMENT_ATTRIBUTE_FK_PRODUCT_ATTRIBUTE_KEY => $this
                ->haveProductAttributeKeyEntity($productAttributeKeySeed)
                ->getIdProductAttributeKey(),
        ];

        $productManagementAttributeEntity = (new SpyProductManagementAttributeQuery())
            ->filterByFkProductAttributeKey($seedData[static::COLUMN_PRODUCT_MANAGEMENT_ATTRIBUTE_FK_PRODUCT_ATTRIBUTE_KEY])
            ->findOneOrCreate();
        $productManagementAttributeEntity->fromArray($seedData);
        $productManagementAttributeEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productManagementAttributeEntity): void {
            $productManagementAttributeEntity->delete();
        });

        return $productManagementAttributeEntity;
    }

    /**
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer[]
     */
    public function generateLocalizedAttributes(): array
    {
        $results = [];
        $data = $this->getSampleLocalizedProductAttributeValues();
        unset($data[ProductAttributeConfig::DEFAULT_LOCALE]);

        foreach ($data as $localizedData) {
            $localeTransfer = $this->getLocaleTransfer();

            $localizedAttributeTransfer = new LocalizedAttributesTransfer();
            $localizedAttributeTransfer->setAttributes($localizedData);
            $localizedAttributeTransfer->setLocale($localeTransfer);
            $localizedAttributeTransfer->setName('product-' . rand(1, 1000));

            $results[] = $localizedAttributeTransfer;
        }

        return $results;
    }

    /**
     * @return array
     */
    protected function getSampleLocalizedProductAttributeValues(): array
    {
        $localeTransfer = $this->getLocaleTransfer();

        $result = [
            '_' => [
                'foo' => 'Foo Value',
                'bar' => '20 units',
            ],
            $localeTransfer->getLocaleName() => [
                'foo' => 'Foo Value DE',
            ],
        ];

        ksort($result);

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(): LocaleTransfer
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getLocator()->product()->facade();
    }
}

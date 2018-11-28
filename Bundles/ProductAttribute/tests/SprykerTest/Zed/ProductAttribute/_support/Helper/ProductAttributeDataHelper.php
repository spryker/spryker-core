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
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductAttributeDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function generateProductManagementAttributeTransfer(array $seedData = [])
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeBuilder($seedData))->build();

        return $productManagementAttributeTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer
     */
    public function generateLocalizedProductManagementAttributeKeyTransfer(array $seedData = [])
    {
        $localizedProductManagementAttributeKeyTransfer = (new LocalizedProductManagementAttributeKeyBuilder($seedData))->build();

        return $localizedProductManagementAttributeKeyTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKey
     */
    public function haveProductAttributeKeyEntity(array $seedData = [])
    {
        $seedData = $seedData + ['key' => md5(microtime())];

        $productAttributeKeyEntity = new SpyProductAttributeKey();
        $productAttributeKeyEntity->fromArray($seedData);
        $productAttributeKeyEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productAttributeKeyEntity) {
            $productAttributeKeyEntity->delete();
        });

        return $productAttributeKeyEntity;
    }

    /**
     * @param array $seedData
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute
     */
    public function haveProductManagementAttributeEntity(array $seedData = [])
    {
        $seedData = $seedData + [
            'input_type' => 'bar',
            'fk_product_attribute_key' => $this->haveProductAttributeKeyEntity()->getIdProductAttributeKey(),
        ];

        $productManagementAttributeEntity = new SpyProductManagementAttribute();
        $productManagementAttributeEntity->fromArray($seedData);
        $productManagementAttributeEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productManagementAttributeEntity) {
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

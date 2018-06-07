<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\LocalizedProductManagementAttributeKeyBuilder;
use Generated\Shared\DataBuilder\ProductManagementAttributeBuilder;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductAttributeDataHelper extends Module
{
    use DataCleanupHelperTrait;

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
}

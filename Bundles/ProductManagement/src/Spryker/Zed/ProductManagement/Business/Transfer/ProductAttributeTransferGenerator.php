<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Transfer;

use Generated\Shared\Transfer\ProductManagementAttributeInputTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeLocalizedTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTypeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInput;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalized;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadata;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeType;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValue;
use Propel\Runtime\Collection\ObjectCollection;

class ProductAttributeTransferGenerator implements ProductAttributeTransferGeneratorInterface
{

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttribute $productAttributeEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function convertProductAttribute(SpyProductManagementAttribute $productAttributeEntity)
    {
        $productAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->fromArray($productAttributeEntity->toArray(), true);

        $productAttributeTransfer->setKey($productAttributeEntity->getSpyProductAttributeKey()->getKey());

        return $productAttributeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttribute[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function convertProductAttributeCollection(ObjectCollection $productAttributeEntityCollection)
    {
        $transferList = [];
        foreach ($productAttributeEntityCollection as $productAttributeEntity) {
            $transferList[] = $this->convertProductAttribute($productAttributeEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValue $productAttributeValueEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer
     */
    public function convertProductAttributeValue(SpyProductManagementAttributeValue $productAttributeValueEntity)
    {
        $productAttributeTransfer = (new ProductManagementAttributeValueTransfer())
            ->fromArray($productAttributeValueEntity->toArray(), true);

        return $productAttributeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValue[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeValueEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function convertProductAttributeValueCollection(ObjectCollection $productAttributeValueEntityCollection)
    {
        $transferList = [];
        foreach ($productAttributeValueEntityCollection as $productAttributeValueEntity) {
            $transferList[] = $this->convertProductAttributeValue($productAttributeValueEntity);
        }

        return $transferList;
    }

}

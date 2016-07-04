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

        $metadataTransfer = $this->convertProductAttributeMetadata($productAttributeEntity->getSpyProductManagementAttributeMetadata());
        $productAttributeTransfer->setMetadata($metadataTransfer);

        $inputTransfer = $this->convertProductAttributeInput($productAttributeEntity->getSpyProductManagementAttributeInput());
        $productAttributeTransfer->setInput($inputTransfer);

        $localizedAttributeCollection = $this->convertProductAttributeLocalizedCollection($productAttributeEntity->getSpyProductManagementAttributeLocalizeds());
        $productAttributeTransfer->setLocalizedAttributes(new \ArrayObject($localizedAttributeCollection));

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
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalized $productAbstractLocalizedEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeLocalizedTransfer
     */
    public function convertProductAttributeLocalized(SpyProductManagementAttributeLocalized $productAbstractLocalizedEntity)
    {
        $productManagementAttributeLocalizedTransfer = (new ProductManagementAttributeLocalizedTransfer())
            ->fromArray($productAbstractLocalizedEntity->toArray(), true);

        return $productManagementAttributeLocalizedTransfer;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalized[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeLocalizedEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeLocalizedTransfer[]
     */
    public function convertProductAttributeLocalizedCollection(ObjectCollection $productAttributeLocalizedEntityCollection)
    {
        $transferList = [];
        foreach ($productAttributeLocalizedEntityCollection as $productAttributeLocalizedEntity) {
            $transferList[] = $this->convertProductAttributeLocalized($productAttributeLocalizedEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadata $productAttributeMetadataEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer
     */
    public function convertProductAttributeMetadata(SpyProductManagementAttributeMetadata $productAttributeMetadataEntity)
    {
        $productAttributeMetadataTransfer = (new ProductManagementAttributeMetadataTransfer())
            ->fromArray($productAttributeMetadataEntity->toArray(), true);

        $typeEntity = $productAttributeMetadataEntity->getSpyProductManagementAttributeType();
        $typeTransfer = $this->convertProductAttributeType($typeEntity);
        $productAttributeMetadataTransfer->setType($typeTransfer);

        return $productAttributeMetadataTransfer;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadata[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeMetadataEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[]
     */
    public function convertProductAttributeMetadataCollection(ObjectCollection $productAttributeMetadataEntityCollection)
    {
        $transferList = [];
        foreach ($productAttributeMetadataEntityCollection as $productAttributeMetadataEntity) {
            $transferList[] = $this->convertProductAttributeMetadata($productAttributeMetadataEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInput $productAttributeInputEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeInputTransfer
     */
    public function convertProductAttributeInput(SpyProductManagementAttributeInput $productAttributeInputEntity)
    {
        $productAttributeTransfer = (new ProductManagementAttributeInputTransfer())
            ->fromArray($productAttributeInputEntity->toArray(), true);

        return $productAttributeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInput[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeInputEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeInputTransfer[]
     */
    public function convertProductAttributeInputCollection(ObjectCollection $productAttributeInputEntityCollection)
    {
        $transferList = [];
        foreach ($productAttributeInputEntityCollection as $productAttributeInputEntity) {
            $transferList[] = $this->convertProductAttributeInput($productAttributeInputEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeType $productAttributeTypeEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTypeTransfer
     */
    public function convertProductAttributeType(SpyProductManagementAttributeType $productAttributeTypeEntity)
    {
        $productAttributeTransfer = (new ProductManagementAttributeTypeTransfer())
            ->fromArray($productAttributeTypeEntity->toArray(), true);

        return $productAttributeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeType[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeTypeEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTypeTransfer[]
     */
    public function convertProductAttributeTypeCollection(ObjectCollection $productAttributeTypeEntityCollection)
    {
        $transferList = [];
        foreach ($productAttributeTypeEntityCollection as $productAttributeTypeEntity) {
            $transferList[] = $this->convertProductAttributeType($productAttributeTypeEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValue $productAttributeValueEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
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

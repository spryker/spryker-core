<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Exception;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValue;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeValueWriter implements AttributeValueWriterInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     */
    public function __construct(ProductManagementQueryContainerInterface $productManagementQueryContainer)
    {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function saveProductAttributeValues(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $existingAttributeValues = $this->getExistingAttributeValues($productManagementAttributeTransfer->getIdProductManagementAttribute());

        $this->productManagementQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            foreach ($productManagementAttributeTransfer->getValues() as $attributeValueTransfer) {
                if (isset($existingAttributeValues[$attributeValueTransfer->getValue()])) {
                    unset($existingAttributeValues[$attributeValueTransfer->getValue()]);
                    continue;
                }

                $attributeValueTransfer->setFkProductManagementAttribute($productManagementAttributeTransfer->getIdProductManagementAttribute());

                $this->createAttributeValue($attributeValueTransfer);
            }

            $this->deleteDetachedAttributeValues($existingAttributeValues);

            $this->productManagementQueryContainer
                ->getConnection()
                ->commit();

        } catch (Exception $e) {
            $this->productManagementQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }

        return $productManagementAttributeTransfer;
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductManagement\Persistence\Base\SpyProductManagementAttributeValue[]
     */
    protected function getExistingAttributeValues($idProductManagementAttribute)
    {
        $result = [];

        $attributeValues = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValue()
            ->filterByFkProductManagementAttribute($idProductManagementAttribute)
            ->find();

        foreach ($attributeValues as $attributeValue) {
            $result[$attributeValue->getValue()] = $attributeValue;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer $attributeValueTransfer
     *
     * @return void
     */
    protected function createAttributeValue(ProductManagementAttributeValueTransfer $attributeValueTransfer)
    {
        $attributeValueEntity = new SpyProductManagementAttributeValue();
        $attributeValueEntity->fromArray($attributeValueTransfer->toArray());

        $attributeValueEntity->save();

        $attributeValueTransfer->setIdProductManagementAttributeValue($attributeValueEntity->getIdProductManagementAttributeValue());
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValue[] $attributeValues
     *
     * @return void
     */
    protected function deleteDetachedAttributeValues(array $attributeValues)
    {
        foreach ($attributeValues as $attributeValue) {
            $attributeValue->getSpyProductManagementAttributeValueTranslations()->delete();
            $attributeValue->delete();
        }
    }

}

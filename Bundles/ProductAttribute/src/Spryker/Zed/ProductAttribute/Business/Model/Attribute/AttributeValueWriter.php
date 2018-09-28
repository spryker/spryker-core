<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

use Exception;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface;

class AttributeValueWriter implements AttributeValueWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @param \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface $productManagementQueryContainer
     */
    public function __construct(ProductAttributeQueryContainerInterface $productManagementQueryContainer)
    {
        $this->productAttributeQueryContainer = $productManagementQueryContainer;
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

        $this->productAttributeQueryContainer
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

            $this->productAttributeQueryContainer
                ->getConnection()
                ->commit();
        } catch (Exception $e) {
            $this->productAttributeQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }

        return $productManagementAttributeTransfer;
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue[]
     */
    protected function getExistingAttributeValues($idProductManagementAttribute)
    {
        $result = [];

        $attributeValues = $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValueByAttributeId($idProductManagementAttribute)
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
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue[] $attributeValues
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

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
        $productManagementAttributeValueEntities = $this->getExistingAttributeValues($productManagementAttributeTransfer->getIdProductManagementAttribute());

        $this->productAttributeQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            foreach ($productManagementAttributeTransfer->getValues() as $productManagementAttributeValueTransfer) {
                $this->createProductManagementAttributeValueIfNotExists(
                    $productManagementAttributeValueTransfer,
                    $productManagementAttributeTransfer,
                    $productManagementAttributeValueEntities,
                );
            }
            $this->deleteDetachedAttributeValues(
                $this->getProductManagementAttributeValueEntitiesToDetach(
                    $productManagementAttributeTransfer,
                    $productManagementAttributeValueEntities,
                ),
            );

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
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param array<string, \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue> $productManagementAttributeValueEntities
     *
     * @return array<string, \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue>
     */
    protected function getProductManagementAttributeValueEntitiesToDetach(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        array $productManagementAttributeValueEntities
    ): array {
        foreach ($productManagementAttributeTransfer->getValues() as $productManagementAttributeValueTransfer) {
            $attributeValue = $productManagementAttributeValueTransfer->getValue();
            if (isset($productManagementAttributeValueEntities[$attributeValue])) {
                unset($productManagementAttributeValueEntities[$attributeValue]);
            }
        }

        return $productManagementAttributeValueEntities;
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return array<string, \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue>
     */
    protected function getExistingAttributeValues($idProductManagementAttribute): array
    {
        /** @var array<string, \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue> $result */
        $result = [];

        $attributeValues = $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValueByAttributeId($idProductManagementAttribute)
            ->find();

        /** @var \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue $attributeValue */
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
    protected function createAttributeValue(ProductManagementAttributeValueTransfer $attributeValueTransfer): void
    {
        $attributeValueEntity = new SpyProductManagementAttributeValue();
        $attributeValueEntity->fromArray($attributeValueTransfer->toArray());

        $attributeValueEntity->save();

        $attributeValueTransfer->setIdProductManagementAttributeValue($attributeValueEntity->getIdProductManagementAttributeValue());
    }

    /**
     * @param array<string, \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue> $attributeValues
     *
     * @return void
     */
    protected function deleteDetachedAttributeValues(array $attributeValues): void
    {
        foreach ($attributeValues as $attributeValue) {
            $attributeValue->getSpyProductManagementAttributeValueTranslations()->delete();
            $attributeValue->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param array<string, \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue> $productManagementAttributeValueEntities
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer
     */
    protected function createProductManagementAttributeValueIfNotExists(
        ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer,
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        array $productManagementAttributeValueEntities
    ): ProductManagementAttributeValueTransfer {
        $attributeValue = $productManagementAttributeValueTransfer->getValue();

        if (isset($productManagementAttributeValueEntities[$attributeValue])) {
            $productManagementAttributeValueTransfer->setIdProductManagementAttributeValue(
                $productManagementAttributeValueEntities[$attributeValue]->getIdProductManagementAttributeValue(),
            );

            return $productManagementAttributeValueTransfer;
        }

        $productManagementAttributeValueTransfer->setFkProductManagementAttribute($productManagementAttributeTransfer->getIdProductManagementAttribute());
        $this->createAttributeValue($productManagementAttributeValueTransfer);

        return $productManagementAttributeValueTransfer;
    }
}

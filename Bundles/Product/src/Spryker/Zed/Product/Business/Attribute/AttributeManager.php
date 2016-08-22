<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Orm\Zed\Product\Persistence\SpyProductAttributesMetadata;
use Orm\Zed\Product\Persistence\SpyProductAttributeType;
use Spryker\Zed\Product\Business\Exception\AttributeExistsException;
use Spryker\Zed\Product\Business\Exception\AttributeTypeExistsException;
use Spryker\Zed\Product\Business\Exception\MissingAttributeTypeException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class AttributeManager implements AttributeManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param string $attributeName
     *
     * @return bool
     */
    public function hasAttribute($attributeName)
    {
        $attributeQuery = $this->productQueryContainer->queryAttributeByName($attributeName);

        return $attributeQuery->count() > 0;
    }

    /**
     * @param string $attributeType
     *
     * @return bool
     */
    public function hasAttributeType($attributeType)
    {
        $attributeTypeQuery = $this->productQueryContainer->queryAttributeTypeByName($attributeType);

        return $attributeTypeQuery->count() > 0;
    }

    /**
     * @param string $attributeType
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingAttributeTypeException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeType
     */
    protected function getAttributeType($attributeType)
    {
        $attributeTypeQuery = $this->productQueryContainer->queryAttributeTypeByName($attributeType);
        $attributeType = $attributeTypeQuery->findOne();

        if (!$attributeType) {
            throw new MissingAttributeTypeException(
                sprintf(
                    'Tried to retrieve a missing attribute type: %s',
                    $attributeType
                )
            );
        }

        return $attributeType;
    }

    /**
     * @param string $attributeName
     * @param string $attributeType
     * @param bool $isEditable
     *
     * @return int
     */
    public function createAttribute($attributeName, $attributeType, $isEditable = true)
    {
        $this->checkAttributeDoesNotExist($attributeName);

        $attributeTypeId = $this->getAttributeType($attributeType)->getPrimaryKey();

        $attributeEntity = (new SpyProductAttributesMetadata())
            ->setKey($attributeName)
            ->setFkType($attributeTypeId)
            ->setIsEditable($isEditable);

        $attributeEntity->save();

        return $attributeEntity->getPrimaryKey();
    }

    /**
     * @param string $attributeName
     *
     * @throws \Spryker\Zed\Product\Business\Exception\AttributeExistsException
     *
     * @return void
     */
    protected function checkAttributeDoesNotExist($attributeName)
    {
        if ($this->hasAttribute($attributeName)) {
            throw new AttributeExistsException(
                sprintf(
                    'Tried to create an attribute that already exists: %s',
                    $attributeName
                )
            );
        }
    }

    /**
     * @param string $name
     * @param string $inputType
     * @param int|null $fkParentAttributeType
     *
     * @return int
     */
    public function createAttributeType($name, $inputType, $fkParentAttributeType = null)
    {
        $this->checkAttributeTypeDoesNotExist($name);

        $attributeTypeEntity = (new SpyProductAttributeType());
        $attributeTypeEntity
            ->setName($name)
            ->setInputRepresentation($inputType)
            ->setFkProductAttributeTypeParent($fkParentAttributeType);

        $attributeTypeEntity->save();

        return $attributeTypeEntity->getPrimaryKey();
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Product\Business\Exception\AttributeTypeExistsException
     *
     * @return void
     */
    private function checkAttributeTypeDoesNotExist($name)
    {
        if ($this->hasAttributeType($name)) {
            throw new AttributeTypeExistsException(
                sprintf(
                    'Tried to create an attribute type that already exists: %s',
                    $name
                )
            );
        }
    }

}

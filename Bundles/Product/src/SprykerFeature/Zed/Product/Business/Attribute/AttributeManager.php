<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Attribute;

use SprykerFeature\Zed\Product\Business\Exception\AttributeExistsException;
use SprykerFeature\Zed\Product\Business\Exception\AttributeTypeExistsException;
use SprykerFeature\Zed\Product\Business\Exception\MissingAttributeTypeException;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributesMetadata;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributeType;

class AttributeManager implements AttributeManagerInterface
{

    /**
     * @var ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param ProductQueryContainerInterface $productQueryContainer
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
     * @throws MissingAttributeTypeException
     *
     * @return SpyProductAttributeType
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
     * @throws AttributeExistsException
     * @throws MissingAttributeTypeException
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
            ->setIsEditable($isEditable)
        ;

        $attributeEntity->save();

        return $attributeEntity->getPrimaryKey();
    }

    /**
     * @param string $attributeName
     *
     * @throws AttributeExistsException
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
     * @throws AttributeTypeExistsException
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
            ->setFkParentType($fkParentAttributeType)
        ;

        $attributeTypeEntity->save();

        return $attributeTypeEntity->getPrimaryKey();
    }

    /**
     * @param string $name
     *
     * @throws AttributeTypeExistsException
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

<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Attribute;

use SprykerFeature\Zed\Product\Business\Exception\AttributeExistsException;
use SprykerFeature\Zed\Product\Business\Exception\AttributeTypeExistsException;
use SprykerFeature\Zed\Product\Business\Exception\MissingAttributeTypeException;

interface AttributeManagerInterface
{
    /**
     * @param string $attributeName
     *
     * @return bool
     */
    public function hasAttribute($attributeName);

    /**
     * @param string $attributeType
     *
     * @return bool
     */
    public function hasAttributeType($attributeType);

    /**
     * @param string $attributeName
     * @param string $attributeType
     * @param bool $isEditable
     *
     * @return int
     * @throws AttributeExistsException
     * @throws MissingAttributeTypeException
     */
    public function createAttribute($attributeName, $attributeType, $isEditable = true);

    /**
     * @param string $name
     * @param string $inputType
     * @param int|null $fkParentAttributeType
     *
     * @return int
     * @throws AttributeTypeExistsException
     */
    public function createAttributeType($name, $inputType, $fkParentAttributeType = null);
}

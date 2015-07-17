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
     * @throws AttributeExistsException
     * @throws MissingAttributeTypeException
     *
     * @return int
     */
    public function createAttribute($attributeName, $attributeType, $isEditable = true);

    /**
     * @param string $name
     * @param string $inputType
     * @param int|null $fkParentAttributeType
     *
     * @throws AttributeTypeExistsException
     *
     * @return int
     */
    public function createAttributeType($name, $inputType, $fkParentAttributeType = null);

}

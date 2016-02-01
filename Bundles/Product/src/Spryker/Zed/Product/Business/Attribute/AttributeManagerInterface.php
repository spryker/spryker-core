<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Spryker\Zed\Product\Business\Exception\AttributeExistsException;
use Spryker\Zed\Product\Business\Exception\AttributeTypeExistsException;
use Spryker\Zed\Product\Business\Exception\MissingAttributeTypeException;

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
     * @throws \Spryker\Zed\Product\Business\Exception\AttributeExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\MissingAttributeTypeException
     *
     * @return int
     */
    public function createAttribute($attributeName, $attributeType, $isEditable = true);

    /**
     * @param string $name
     * @param string $inputType
     * @param int|null $fkParentAttributeType
     *
     * @throws \Spryker\Zed\Product\Business\Exception\AttributeTypeExistsException
     *
     * @return int
     */
    public function createAttributeType($name, $inputType, $fkParentAttributeType = null);

}

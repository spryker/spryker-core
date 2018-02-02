<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Transfer;

class AbstractEntityTransfer extends AbstractTransfer implements EntityTransferInterface
{
    /**
     * @var null|string
     */
    protected static $entityNamespace = null;

    /**
     * This property is used to map all properties which does not exist in child transfer,
     * it's used for SQL aliases, aggregates, custom fields.
     *
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * @internal
     *
     * Returns FQCN of propel entity it's mapped
     *
     * @return string
     */
    public function entityNamespace()
    {
        return static::$entityNamespace;
    }

    /**
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * @param array $data
     * @param bool $ignoreMissingProperty
     *
     * @return $this
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            if ($this->hasProperty($property, $ignoreMissingProperty) === false) {
                $this->virtualColumns[$property] = $value;
                continue;
            }

            $property = $this->transferPropertyNameMap[$property];

            if ($this->transferMetadata[$property]['is_collection']) {
                $elementType = $this->transferMetadata[$property]['type'];
                $value = $this->processArrayObject($elementType, $value, $ignoreMissingProperty);
            } elseif ($this->transferMetadata[$property]['is_transfer']) {
                $value = $this->initializeNestedTransferObject($property, $value, $ignoreMissingProperty);
            }

            $this->$property = $value;
            $this->modifiedProperties[$property] = true;
        }

        return $this;
    }
}

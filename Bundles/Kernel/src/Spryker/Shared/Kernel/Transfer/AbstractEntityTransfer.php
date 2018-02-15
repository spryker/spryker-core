<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Transfer;

class AbstractEntityTransfer extends AbstractTransfer implements EntityTransferInterface
{
    /**
     * This property is used to map all properties which does not exist in child transfer,
     * it's used for SQL aliases, aggregates, custom fields.
     *
     * @var array
     */
    protected $virtualProperties = [];

    /**
     * @return array
     */
    public function virtualProperties()
    {
        return $this->virtualProperties;
    }

    /**
     * @param array $data
     * @param bool $acceptVirtualProperties
     *
     * @return $this
     */
    public function fromArray(array $data, $acceptVirtualProperties = false)
    {
        foreach ($data as $property => $value) {
            if ($this->hasProperty($property, $acceptVirtualProperties) === false) {
                $this->virtualProperties[$property] = $value;
                continue;
            }

            $property = $this->transferPropertyNameMap[$property];

            if ($this->transferMetadata[$property]['is_collection']) {
                $elementType = $this->transferMetadata[$property]['type'];
                $value = $this->processArrayObject($elementType, $value, $acceptVirtualProperties);
            } elseif ($this->transferMetadata[$property]['is_transfer']) {
                $value = $this->initializeNestedTransferObject($property, $value, $acceptVirtualProperties);
            }

            $this->$property = $value;
            $this->modifiedProperties[$property] = true;
        }

        return $this;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Spryker\Shared\Library\Collection\Collection;
use Spryker\Shared\Library\Collection\CollectionInterface;
use Spryker\Shared\ProductManagement\ProductManagementConstants;

class AttributeProcessor implements AttributeProcessorInterface
{

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $abstractAttributes;

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $abstractLocalizedAttributes;

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $concreteAttributes;

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $concreteLocalizedAttributes;

    /**
     * @param array $abstractAttributes
     * @param array $attributes
     * @param array $localizedAttributes
     * @param array $abstractLocalizedAttributes
     */
    public function __construct(
        array $abstractAttributes = [],
        array $attributes = [],
        array $localizedAttributes = [],
        array $abstractLocalizedAttributes = []
    ) {
        $this->abstractAttributes = new Collection($abstractAttributes);
        $this->concreteAttributes =  new Collection($attributes);
        $this->concreteLocalizedAttributes =  new Collection($localizedAttributes);
        $this->abstractLocalizedAttributes =  new Collection($abstractLocalizedAttributes);
    }

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getAbstractAttributes()
    {
        return $this->abstractAttributes;
    }

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $abstractAttributes
     *
     * @return $this
     */
    public function setAbstractAttributes(CollectionInterface $abstractAttributes)
    {
        $this->abstractAttributes = $abstractAttributes;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getConcreteAttributes()
    {
        return $this->concreteAttributes;
    }

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $concreteAttributes
     *
     * @return $this
     */
    public function setConcreteAttributes(CollectionInterface $concreteAttributes)
    {
        $this->concreteAttributes = $concreteAttributes;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getConcreteLocalizedAttributes()
    {
        return $this->concreteLocalizedAttributes;
    }

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $concreteLocalizedAttributes
     *
     * @return $this
     */
    public function setConcreteLocalizedAttributes(CollectionInterface $concreteLocalizedAttributes)
    {
        $this->concreteLocalizedAttributes = $concreteLocalizedAttributes;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getAbstractLocalizedAttributes()
    {
        return $this->abstractLocalizedAttributes;
    }

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $abstractLocalizedAttributes
     *
     * @return $this
     */
    public function setAbstractLocalizedAttributes(CollectionInterface $abstractLocalizedAttributes)
    {
        $this->abstractLocalizedAttributes = $abstractLocalizedAttributes;

        return $this;
    }

    /**
     * @return array
     */
    public function mergeAttributes()
    {
        $abstractAttributes = $this->mergeAbstractAttributes();
        $concreteAttributes = $this->mergeConcreteAttributes();

        return array_merge($abstractAttributes, $concreteAttributes);
    }

    /**
     * @return array
     */
    public function mergeAbstractAttributes()
    {
        $abstractAttributes = $this->getAbstractAttributes()->toArray(true);
        $abstractLocalizedAttributes = $this->getAbstractLocalizedAttributes()->toArray(true);

        return array_merge($abstractAttributes, $abstractLocalizedAttributes);
    }

    /**
     * @return array
     */
    public function mergeConcreteAttributes()
    {
        $concreteAttributes = $this->getConcreteAttributes()->toArray(true);
        $concreteLocalizedAttributes = $this->getConcreteLocalizedAttributes()->toArray(true);

        return array_merge($concreteAttributes, $concreteLocalizedAttributes);
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function generateAttributeValues(array $values)
    {
        $defaults = $values[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE];
        unset($values[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE]);

        foreach ($values as $localeCode => $data) {
            $values[$localeCode] = array_merge($data, $defaults);
        }

        return $values;
    }

}

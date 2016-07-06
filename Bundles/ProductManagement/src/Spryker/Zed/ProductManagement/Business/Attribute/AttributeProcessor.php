<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Spryker\Shared\Library\Collection\Collection;
use Spryker\Shared\Library\Collection\CollectionInterface;

class AttributeProcessor implements AttributeProcessorInterface
{

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $abstractAttributes;

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $attributes;

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $localizedAttributes;

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $abstractLocalizedAttributes;

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
        $this->attributes =  new Collection($attributes);
        $this->localizedAttributes =  new Collection($localizedAttributes);
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
     * @return void
     */
    public function setAbstractAttributes(CollectionInterface $abstractAttributes)
    {
        $this->abstractAttributes = $abstractAttributes;
    }

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $attributes
     */
    public function setAttributes(CollectionInterface $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getLocalizedAttributes()
    {
        return $this->localizedAttributes;
    }

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $localizedAttributes
     * @return void
     */
    public function setLocalizedAttributes(CollectionInterface $localizedAttributes)
    {
        $this->localizedAttributes = $localizedAttributes;
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
     * @return void
     */
    public function setAbstractLocalizedAttributes(CollectionInterface $abstractLocalizedAttributes)
    {
        $this->abstractLocalizedAttributes = $abstractLocalizedAttributes;
    }

    /**
     * @return array
     */
    public function mergeAttributes()
    {
        $abstractAttributes = $this->getAbstractAttributes()->toArray(true);
        $attributes = $this->getAttributes()->toArray(true);
        $abstractLocalizedAttributes = $this->getAbstractLocalizedAttributes()->toArray(true);
        $localizedAttributes = $this->getLocalizedAttributes()->toArray(true);

        $mergedAbstractAttributes = array_merge($abstractAttributes, $attributes);
        $mergedLocalizedAttributes = array_merge($abstractLocalizedAttributes, $localizedAttributes);

        return array_merge($mergedAbstractAttributes, $mergedLocalizedAttributes);
    }

}

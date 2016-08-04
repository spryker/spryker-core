<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;

class AttributeProcessor implements AttributeProcessorInterface
{

    /**
     * @var array
     */
    protected $abstractAttributes;

    /**
     * @var array
     */
    protected $abstractLocalizedAttributes;

    /**
     * @var array
     */
    protected $concreteAttributes;

    /**
     * @var array
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
        $this->abstractAttributes = $abstractAttributes;
        $this->concreteAttributes =  $attributes;
        $this->concreteLocalizedAttributes = $localizedAttributes;
        $this->abstractLocalizedAttributes = $abstractLocalizedAttributes;
    }

    /**
     * @return array
     */
    public function getAbstractAttributes()
    {
        return $this->abstractAttributes;
    }

    /**
     * @param array $abstractAttributes
     *
     * @return $this
     */
    public function setAbstractAttributes(array $abstractAttributes)
    {
        $this->abstractAttributes = $abstractAttributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getConcreteAttributes()
    {
        return $this->concreteAttributes;
    }

    /**
     * @param array $concreteAttributes
     *
     * @return $this
     */
    public function setConcreteAttributes(array $concreteAttributes)
    {
        $this->concreteAttributes = $concreteAttributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getConcreteLocalizedAttributes()
    {
        return $this->concreteLocalizedAttributes;
    }

    /**
     * @param array $concreteLocalizedAttributes
     *
     * @return $this
     */
    public function setConcreteLocalizedAttributes(array $concreteLocalizedAttributes)
    {
        $this->concreteLocalizedAttributes = $concreteLocalizedAttributes;

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer[]
     */
    public function getAbstractLocalizedAttributes()
    {
        return $this->abstractLocalizedAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $abstractLocalizedAttributes
     *
     * @return $this
     */
    public function setAbstractLocalizedAttributes(array $abstractLocalizedAttributes)
    {
        $this->abstractLocalizedAttributes = $abstractLocalizedAttributes;

        return $this;
    }

    /**
     * @return array
     */
    public function mergeAttributes()
    {
        sd('fix me');
        $attributes = $this->mergeAbstractLocalizedAttributes();
        $localizedAttributes = $this->mergeLocalizedAttributes();

        return array_merge($attributes, $localizedAttributes);
    }

    /**
     * @param null|string $localeCode
     *
     * @return array
     */
    public function mergeAbstractLocalizedAttributes($localeCode = null)
    {
        $mergedAttributes = [];
        foreach ($this->getAbstractLocalizedAttributes() as $transfer) {
            if ($localeCode === $transfer->getLocale()->getLocaleName()) {
                $mergedAttributes[$transfer->getLocale()->getLocaleName()] = $transfer->getAttributes();
            }
        }

        return $mergedAttributes;
    }

    /**
     * @return array
     */
    public function getAllAbstractKeys()
    {
        $mergedAttributes = [];
        foreach ($this->getAbstractLocalizedAttributes() as $transfer) {
            $mergedAttributes += $transfer->getAttributes();
        }

        foreach ($this->getConcreteLocalizedAttributes() as $localeCode => $localizedAttributes) {
            $mergedAttributes += $localizedAttributes;
        }

        $mergedAttributes += $this->getAbstractAttributes();
        $mergedAttributes += $this->getConcreteAttributes();

        return array_combine(
            array_keys($mergedAttributes),
            array_fill(0, count($mergedAttributes), null)
        );
    }

    /**
     * @return array
     */
    public function mergeLocalizedAttributes()
    {
        $concreteAttributes = $this->getConcreteAttributes();
        $concreteLocalizedAttributes = $this->getConcreteLocalizedAttributes();

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

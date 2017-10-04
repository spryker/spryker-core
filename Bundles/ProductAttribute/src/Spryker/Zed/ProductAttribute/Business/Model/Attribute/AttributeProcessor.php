<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

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
     * @param array $concreteAttributes
     * @param array $abstractLocalizedAttributes
     * @param array $concreteLocalizedAttributes
     */
    public function __construct(
        array $abstractAttributes = [],
        array $concreteAttributes = [],
        array $abstractLocalizedAttributes = [],
        array $concreteLocalizedAttributes = []
    ) {
        $this->abstractAttributes = $abstractAttributes;
        $this->concreteAttributes = $concreteAttributes;
        $this->abstractLocalizedAttributes = $abstractLocalizedAttributes;
        $this->concreteLocalizedAttributes = $concreteLocalizedAttributes;
    }

    /**
     * @return array
     */
    public function getAbstractAttributes()
    {
        return $this->abstractAttributes;
    }

    /**
     * Key value pairs, eg. ['foo' => 'bar']
     *
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
     * Key value pairs, eg. ['foo' => 'bar']
     *
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
     * Key value pairs, with locale names as primary keys, eg. [ 'de_DE' => ['foo' => 'bar']]
     *
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
     * @return array
     */
    public function getAbstractLocalizedAttributes()
    {
        return $this->abstractLocalizedAttributes;
    }

    /**
     * Key value pairs, eg. ['foo' => 'bar']
     *
     * @param array $abstractLocalizedAttributes
     *
     * @return $this
     */
    public function setAbstractLocalizedAttributes(array $abstractLocalizedAttributes)
    {
        $this->abstractLocalizedAttributes = $abstractLocalizedAttributes;

        return $this;
    }

    /**
     * @param null|string $localeCode
     *
     * @return array
     */
    public function mergeAttributes($localeCode = null)
    {
        $abstractAttributes = $this->getAbstractAttributes();
        $concreteAttributes = $this->getConcreteAttributes();

        if ($localeCode !== null) {
            $abstractLocalizedAttributes = $this->getAbstractLocalizedAttributesByLocaleCode($localeCode);
            $abstractAttributes = array_merge($abstractAttributes, $abstractLocalizedAttributes);

            $concreteLocalizedAttributes = $this->getConcreteLocalizedAttributesByLocaleCode($localeCode);
            $concreteAttributes = array_merge($concreteAttributes, $concreteLocalizedAttributes);
        }

        $result = array_merge($abstractAttributes, $concreteAttributes);
        ksort($result);

        return $result;
    }

    /**
     * @param string $localeCode
     *
     * @return array
     */
    public function getAbstractLocalizedAttributesByLocaleCode($localeCode)
    {
        if (array_key_exists($localeCode, $this->getAbstractLocalizedAttributes())) {
            return $this->getAbstractLocalizedAttributes()[$localeCode];
        }

        return [];
    }

    /**
     * @param string $localeCode
     *
     * @return array
     */
    public function getConcreteLocalizedAttributesByLocaleCode($localeCode)
    {
        if (array_key_exists($localeCode, $this->getConcreteLocalizedAttributes())) {
            return $this->getConcreteLocalizedAttributes()[$localeCode];
        }

        return [];
    }

    /**
     * @return array
     */
    public function getAllKeys()
    {
        $mergedAttributes = [];
        foreach ($this->getAbstractLocalizedAttributes() as $localeCode => $data) {
            $mergedAttributes += $data;
        }

        foreach ($this->getConcreteLocalizedAttributes() as $localeCode => $data) {
            $mergedAttributes += $data;
        }

        $mergedAttributes += $this->getAbstractAttributes();
        $mergedAttributes += $this->getConcreteAttributes();

        return array_combine(
            array_keys($mergedAttributes),
            array_fill(0, count($mergedAttributes), null)
        );
    }

}

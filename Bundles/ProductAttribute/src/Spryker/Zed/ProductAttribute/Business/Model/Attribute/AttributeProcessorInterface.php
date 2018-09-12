<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

interface AttributeProcessorInterface
{
    /**
     * @return array
     */
    public function getAbstractAttributes();

    /**
     * Key value pairs, eg. ['foo' => 'bar']
     *
     * @param array $abstractAttributes
     *
     * @return $this
     */
    public function setAbstractAttributes(array $abstractAttributes);

    /**
     * @return array
     */
    public function getConcreteAttributes();

    /**
     * Key value pairs, eg. ['foo' => 'bar']
     *
     * @param array $concreteAttributes
     *
     * @return $this
     */
    public function setConcreteAttributes(array $concreteAttributes);

    /**
     * @return array
     */
    public function getConcreteLocalizedAttributes();

    /**
     * Key value pairs, with locale names as primary keys, eg. [ 'de_DE' => ['foo' => 'bar']]
     *
     * @param array $concreteLocalizedAttributes
     *
     * @return $this
     */
    public function setConcreteLocalizedAttributes(array $concreteLocalizedAttributes);

    /**
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer[]
     */
    public function getAbstractLocalizedAttributes();

    /**
     * Key value pairs, eg. ['foo' => 'bar']
     *
     * @param array $abstractLocalizedAttributes
     *
     * @return $this
     */
    public function setAbstractLocalizedAttributes(array $abstractLocalizedAttributes);

    /**
     * @param null|string $localeCode
     *
     * @return array
     */
    public function mergeAttributes($localeCode = null);

    /**
     * @param string $localeCode
     *
     * @return array
     */
    public function getAbstractLocalizedAttributesByLocaleCode($localeCode);

    /**
     * @param string $localeCode
     *
     * @return array
     */
    public function getConcreteLocalizedAttributesByLocaleCode($localeCode);

    /**
     * @return array
     */
    public function getAllKeys();
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;

interface AttributeProcessorInterface
{

    /**
     * @return array
     */
    public function getAbstractAttributes();

    /**
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
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $abstractLocalizedAttributes
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

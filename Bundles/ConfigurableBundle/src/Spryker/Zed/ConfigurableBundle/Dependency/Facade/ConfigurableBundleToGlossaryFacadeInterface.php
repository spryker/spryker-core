<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ConfigurableBundleToGlossaryFacadeInterface
{
    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation($keyName, $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function createKey($keyName);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, ?LocaleTransfer $locale = null);

    /**
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], ?LocaleTransfer $localeTransfer = null);
}

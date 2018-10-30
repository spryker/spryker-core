<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Provider;

use Generated\Shared\Transfer\LocaleTransfer;

interface LocaleProviderInterface
{
    /**
     * @param bool $includeDefault
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection($includeDefault = false): array;

    /**
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleTransfer($localeCode): LocaleTransfer;

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale(): LocaleTransfer;

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale(string $localeName): bool;

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createDefaultLocale(): LocaleTransfer;
}

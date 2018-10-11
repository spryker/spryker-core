<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Dependency\Facade;

interface CategoryImageToLocaleInterface
{
    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale();

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName);

    /**
     * @return array
     */
    public function getAvailableLocales();

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection();

    /**
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleById($idLocale);
}

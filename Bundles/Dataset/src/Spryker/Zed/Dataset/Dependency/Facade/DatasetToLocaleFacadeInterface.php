<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Facade;

interface DatasetToLocaleFacadeInterface
{
    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName);

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName);

    /**
     * @param string $localeCode
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleByCode($localeCode);

    /**
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleById($idLocale);

    /**
     * @return string
     */
    public function getCurrentLocaleName();

    /**
     * @return array
     */
    public function getAvailableLocales();

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale();

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\LocaleExistsException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale($localeName);

    /**
     * @param string $localeName
     *
     * @return void
     */
    public function deleteLocale($localeName);

    /**
     * @return void
     */
    public function install();

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection();
}

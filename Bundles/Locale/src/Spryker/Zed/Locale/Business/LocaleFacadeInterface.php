<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleTransfer;

interface LocaleFacadeInterface
{
    /**
     * Specification:
     * - Checks if the given $localeName exists in database or not.
     * - Returns true if it exists, false otherwise.
     *
     * @api
     *
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName);

    /**
     * Specification:
     * - Reads persisted locale by given locale name.
     * - Returns a LocaleTransfer if it's found, throws exception otherwise.
     *
     * @api
     *
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName);

    /**
     * Specification:
     * - Returns a LocaleTransfer with the data of the currently used locale.
     *
     * @api
     *
     * @param string $localeCode
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleByCode($localeCode);

    /**
     * Specification:
     * - Reads persisted locale by given locale id
     * - Returns a LocaleTransfer if it's found, throws exception otherwise.
     *
     * @api
     *
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleById($idLocale);

    /**
     * Specification:
     * - Returns the name of the currently used locale.
     *
     * @api
     *
     * @return string
     */
    public function getCurrentLocaleName();

    /**
     * Specification:
     * - Returns an associative array of [id_locale => locale_name] pairs.
     * - The locales returned are read from the store configuration and their data is read from database.
     *
     * @api
     *
     * @return array
     */
    public function getAvailableLocales();

    /**
     * Specification:
     * - Returns a LocaleTransfer with the data of the currently used locale.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale();

    /**
     * Specification:
     * - Persists a new locale entity to database.
     * - The locale name must be unique otherwise exception is thrown.
     * - Returns a LocaleTransfer with the data of the persisted locale.
     *
     * @api
     *
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\LocaleExistsException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale($localeName);

    /**
     * Specification:
     * - "Soft delete" the locale entity by setting it inactive.
     *
     * @api
     *
     * @param string $localeName
     *
     * @return void
     */
    public function deleteLocale($localeName);

    /**
     * Specification:
     * - Reads a list of predefined locales from a file, specified in the LocaleConfig.
     * - Persists new locale entities from the list to database.
     *
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * Specification:
     * - Returns an associative array of [locale_name => LocaleTransfer] pairs.
     * - The locales returned are read from the store configuration and their data is read from database.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection();

    /**
     * Specification:
     * - Sets current locale;
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function setCurrentLocale(LocaleTransfer $localeTransfer): LocaleTransfer;

    /**
     * Specification:
     * - Returns a string array of names of available locales without accessing the database.
     *
     * @api
     *
     * @return string[]
     */
    public function getAvailableLocaleNames(): array;
}

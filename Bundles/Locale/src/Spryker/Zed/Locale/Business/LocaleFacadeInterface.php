<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
    public function hasLocale(string $localeName): bool;

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
    public function getLocale(string $localeName): LocaleTransfer;

    /**
     * Specification:
     * - Reads persisted locale by given locale id.
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
    public function getLocaleById(int $idLocale): LocaleTransfer;

    /**
     * Specification:
     * - Returns the name of the currently used locale.
     *
     * @api
     *
     * @return string
     */
    public function getCurrentLocaleName(): string;

    /**
     * Specification:
     * - Returns an associative array of [id_locale => locale_name] pairs.
     * - The locales returned are read from the store configuration and their data is read from database.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAvailableLocales(): array;

    /**
     * Specification:
     * - Returns a LocaleTransfer with the data of the currently used locale.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale(): LocaleTransfer;

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
    public function createLocale(string $localeName): LocaleTransfer;

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
    public function deleteLocale(string $localeName): void;

    /**
     * Specification:
     * - Reads a list of predefined locales from a file, specified in the LocaleConfig.
     * - Persists new locale entities from the list to database.
     *
     * @api
     *
     * @return void
     */
    public function install(): void;

    /**
     * Specification:
     * - Returns an associative array of [locale_name => `LocaleTransfer`] pairs.
     * - The locales returned are read from the store configuration and their data is read from database if LocaleCriteria is not provided.
     * - Returns locales from DB filtered by `LocaleCriteriaTransfer` if it is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleCriteriaTransfer|null $localeCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollection(?LocaleCriteriaTransfer $localeCriteriaTransfer = null): array;

    /**
     * Specification:
     * - Provides list of locale ISO codes available for Backoffice UI.
     * - The list of locales is read from the configuration.
     *
     * @api
     *
     * @return array<int, string>
     */
    public function getSupportedLocaleCodes(): array;

    /**
     * Specification:
     * - Expands collection of store transfers with available locale codes.
     * - Expands collection only if `Dynamic Store` is enabled
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithLocales(array $storeTransfers): array;

    /**
     * Specification:
     * - Updates default locale for store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreDefaultLocale(StoreTransfer $storeTransfer): StoreResponseTransfer;

    /**
     * Specification:
     * - Validates whether default locale is available for store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validateStoreLocale(StoreTransfer $storeTransfer): StoreResponseTransfer;

    /**
     * Specification:
     * - Drops all relation of between store and locales.
     * - Persists new `LocaleStore` entities to a database.
     * - Returns a `StoreResponseTransfer` with the data of the store and its locales.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreLocales(StoreTransfer $storeTransfer): StoreResponseTransfer;
}

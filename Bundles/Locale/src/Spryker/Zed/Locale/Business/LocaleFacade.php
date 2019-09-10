<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Locale\Business\LocaleBusinessFactory getFactory()
 * @method \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface getRepository()
 */
class LocaleFacade extends AbstractFacade implements LocaleFacadeInterface
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
    public function hasLocale($localeName)
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->hasLocale($localeName);
    }

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
    public function getLocale($localeName)
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->getLocale($localeName);
    }

    /**
     * Specification:
     * - Reads persisted locale by given locale name.
     * - Returns a LocaleTransfer if it's found, throws exception otherwise.
     *
     * @api
     *
     * @deprecated Use getLocale($localeName) instead
     *
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleByCode($localeCode)
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->getLocale($localeCode);
    }

    /**
     * Specification:
     * - Reads persisted locale by given locale id
     * - Returns a LocaleTransfer if it's found, throws exception otherwise.
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleById($idLocale)
    {
        return $this->getFactory()
            ->createLocaleManager()
            ->getLocaleById($idLocale);
    }

    /**
     * Specification:
     * - Returns the name of the currently used locale.
     *
     * @api
     *
     * @return string
     */
    public function getCurrentLocaleName()
    {
        return Store::getInstance()->getCurrentLocale();
    }

    /**
     * Specification:
     * - Returns an associative array of [id_locale => locale_name] pairs.
     * - The locales returned are read from the store configuration and their data is read from database.
     *
     * @api
     *
     * @return array
     */
    public function getAvailableLocales()
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->getAvailableLocales();
    }

    /**
     * Specification:
     * - Returns a LocaleTransfer with the data of the currently used locale.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
        $localeName = $this->getCurrentLocaleName();

        return $this->getLocale($localeName);
    }

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
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale($localeName)
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->createLocale($localeName);
    }

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
    public function deleteLocale($localeName)
    {
        $localeManager = $this->getFactory()->createLocaleManager();
        $localeManager->deleteLocale($localeName);
    }

    /**
     * Specification:
     * - Reads a list of predefined locales from a file, specified in the LocaleConfig.
     * - Persists new locale entities from the list to database.
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()->createInstaller()->install();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection()
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->getLocaleCollection();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function setCurrentLocale(LocaleTransfer $localeTransfer): LocaleTransfer
    {
        $this->getFactory()->getStore()->setCurrentLocale($localeTransfer->getLocaleName());

        return $localeTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getAvailableLocalesAsString(): array
    {
        return $this->getFactory()->createLocaleManager()->getAvailableLocalesAsString();
    }
}

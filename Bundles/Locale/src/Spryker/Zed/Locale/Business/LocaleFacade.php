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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
        $localeManager = $this->getFactory()->createLocaleReader();

        return $localeManager->getLocaleByName($localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Locale\Business\LocaleFacade::getLocale()} instead.
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
     * {@inheritDoc}
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
            ->createLocaleReader()
            ->getLocaleById($idLocale);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getAvailableLocales()
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->getAvailableLocales();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
    public function getSupportedLocaleCodes(): array
    {
        return $this->getFactory()->createLocaleManager()->getSupportedLocaleCodes();
    }
}

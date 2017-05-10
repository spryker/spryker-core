<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Dependency\Facade;

class FactFinderToLocaleBridge implements FactFinderToLocaleInterface
{

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * FactFinderToLocaleBridge constructor.
     *
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection()
    {
        return $this->localeFacade
            ->getLocaleCollection();
    }

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName)
    {
        return $this->localeFacade
            ->hasLocale($localeName);
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName)
    {
        return $this->localeFacade
            ->getLocale($localeName);
    }

    /**
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleByCode($localeCode)
    {
        return $this->localeFacade
            ->getLocaleByCode($localeCode);
    }

    /**
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleById($idLocale)
    {
        return $this->localeFacade
            ->getLocaleById($idLocale);
    }

    /**
     * @return string
     */
    public function getCurrentLocaleName()
    {
        return $this->localeFacade
            ->getCurrentLocaleName();
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->localeFacade
            ->getAvailableLocales();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->localeFacade
            ->getCurrentLocale();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale($localeName)
    {
        return $this->localeFacade
            ->createLocale($localeName);
    }

    /**
     * @param string $localeName
     *
     * @return void
     */
    public function deleteLocale($localeName)
    {
        return $this->localeFacade
            ->deleteLocale($localeName);
    }

    /**
     * @return void
     */
    public function install()
    {
        return $this->localeFacade
            ->install();
    }

}

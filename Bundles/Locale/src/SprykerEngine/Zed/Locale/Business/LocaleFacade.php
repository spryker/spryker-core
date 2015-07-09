<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Locale\Business\Exception\LocaleExistsException;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

/**
 * @method LocaleDependencyContainer getDependencyContainer()
 */
class LocaleFacade extends AbstractFacade
{

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName)
    {
        $localeManager = $this->getDependencyContainer()->getLocaleManager();

        return $localeManager->hasLocale($localeName);
    }

    /**
     * @param string $localeName
     *
     * @throws MissingLocaleException
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName)
    {
        $localeManager = $this->getDependencyContainer()->getLocaleManager();

        return $localeManager->getLocale($localeName);
    }

    /**
     * @return string
     */
    public function getCurrentLocaleName()
    {
        return \SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLocale();
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        $availableLocales = Store::getInstance()->getLocales();
        $locales = [];
        foreach ($availableLocales as $localeName) {
            $localeInfo = $this->getLocale($localeName);
            $locales[$localeInfo->getIdLocale()] = $localeInfo->getLocaleName();
        }

        return $locales;
    }

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale()
    {
        $localeName = $this->getCurrentLocaleName();

        return $this->getLocale($localeName);
    }

    /**
     * @param string $localeName
     *
     * @throws LocaleExistsException
     *
     * @return LocaleTransfer
     */
    public function createLocale($localeName)
    {
        $localeManager = $this->getDependencyContainer()->getLocaleManager();

        return $localeManager->createLocale($localeName);
    }

    /**
     * @param string $localeName
     */
    public function deleteLocale($localeName)
    {
        $localeManager = $this->getDependencyContainer()->getLocaleManager();
        $localeManager->deleteLocale($localeName);
    }

    /**
     * @param MessengerInterface $messenger
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getDependencyContainer()->getInstaller($messenger)->install();
    }

}

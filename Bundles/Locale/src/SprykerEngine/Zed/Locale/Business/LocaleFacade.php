<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Locale\Business\Exception\LocaleExistsException;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;

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
        return \Spryker\Shared\Kernel\Store::getInstance()->getCurrentLocale();
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
     *
     * @return void
     */
    public function deleteLocale($localeName)
    {
        $localeManager = $this->getDependencyContainer()->getLocaleManager();
        $localeManager->deleteLocale($localeName);
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getDependencyContainer()->getInstaller($messenger)->install();
    }

}

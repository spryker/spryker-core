<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Zed\Locale\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
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
     * @return int
     * @throws MissingLocaleException
     */
    public function getIdLocale($localeName)
    {
        $localeManager = $this->getDependencyContainer()->getLocaleManager();
        return $localeManager->getLocale($localeName)->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return \SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLocale();
    }

    /**
     * @return array
     */
    public function getRelevantLocales()
    {
        //TODO retrieve this
        //just some different locales
        return ['de_DE', 'en_US', 'fr_FR', 'de_CH', 'fr_CH'];
    }

    /**
     * @return int
     */
    public function getCurrentIdLocale()
    {
        $localeName = $this->getCurrentLocale();

        return $this->getIdLocale($localeName);
    }

    /**
     * @param string $localeName
     *
     * @return int
     * @throws LocaleExistsException
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

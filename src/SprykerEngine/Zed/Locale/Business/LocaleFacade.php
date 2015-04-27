<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Zed\Locale\Business;

use SprykerEngine\Shared\Dto\LocaleDto;
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
     * @return LocaleDto
     * @throws MissingLocaleException
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
    public function getRelevantLocaleNames()
    {
        //TODO retrieve this
        //just some different locales
        return ['de_DE', 'en_US', 'fr_FR', 'de_CH', 'fr_CH'];
    }

    /**
     * @return LocaleDto
     */
    public function getCurrentLocale()
    {
        $localeName = $this->getCurrentLocaleName();

        return $this->getLocale($localeName);
    }

    /**
     * @param string $localeName
     *
     * @return LocaleDto
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

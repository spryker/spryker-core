<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

/**
 * @method \Spryker\Zed\Locale\Business\LocaleBusinessFactory getFactory()
 */
class LocaleFacade extends AbstractFacade implements LocaleFacadeInterface
{

    /**
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
     * @api
     *
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName)
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->getLocale($localeName);
    }

    /**
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
     * @api
     *
     * @return string
     */
    public function getCurrentLocaleName()
    {
        return Store::getInstance()->getCurrentLocale();
    }

    /**
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
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->createInstaller($messenger)->install();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection()
    {
        $localeManager = $this->getFactory()->createLocaleManager();

        return $localeManager->getLocaleCollection();
    }

}

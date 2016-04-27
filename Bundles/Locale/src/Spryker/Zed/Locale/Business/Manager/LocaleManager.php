<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Manager;

use Orm\Zed\Locale\Persistence\SpyLocale;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\Collection\Collection;
use Spryker\Zed\Locale\Business\Exception\LocaleExistsException;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Locale\Business\TransferGeneratorInterface;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;

class LocaleManager
{

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @var \Spryker\Zed\Locale\Business\TransferGeneratorInterface
     */
    protected $transferGenerator;

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected $localeCache;
    
    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     * @param \Spryker\Zed\Locale\Business\TransferGeneratorInterface $transferGenerator
     */
    public function __construct(
        LocaleQueryContainerInterface $localeQueryContainer,
        TransferGeneratorInterface $transferGenerator
    ) {
        $this->localeQueryContainer = $localeQueryContainer;
        $this->transferGenerator = $transferGenerator;
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName)
    {
        if (!$this->getLocaleCache()->has($localeName)) {
            throw new MissingLocaleException(
                sprintf(
                    'Tried to retrieve locale %s, but it does not exist',
                    $localeName
                )
            );
        }

        return $this->getLocaleCache()->get($localeName);
    }

    /**
     * @deprecated Use getLocale($localeName) instead
     *
     * @param string $localeCode
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleByCode($localeCode)
    {
        return $this->getLocale($localeCode);
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\LocaleExistsException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale($localeName)
    {
        if ($this->hasLocale($localeName)) {
            throw new LocaleExistsException(
                sprintf(
                    'Tried to create locale %s, but it already exists',
                    $localeName
                )
            );
        }

        $locale = new SpyLocale();
        $locale->setLocaleName($localeName);
        $locale->save();

        $localeTransfer = $this->transferGenerator->convertLocale($locale);

        $this->getLocaleCache()->set($localeName, $localeTransfer);

        return $localeTransfer;
    }

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName)
    {
        return $this->getLocaleCache()->has($localeName);
    }

    /**
     * @param string $localeName
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function deleteLocale($localeName)
    {
        if (!$this->hasLocale($localeName)) {
            return true;
        }

        $locale = $this->localeQueryContainer
            ->queryLocaleByName($localeName)
            ->findOne();

        $locale->setIsActive(false);
        $locale->save();

        $this->getLocaleCache()->remove($localeName);

        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection()
    {
        return $this->getLocaleCache()->toArray();
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        $localeCollection = $this->getLocaleCollection();

        $locales = [];
        foreach ($localeCollection as $localeName => $localeInfo) {
            $locales[$localeInfo->getIdLocale()] = $localeInfo->getLocaleName();
        }

        return $locales;
    }

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected function getLocaleCache()
    {
        if ($this->localeCache === null) {
            $this->initLocaleCache();
        }

        return $this->localeCache;
    }

    /**
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return void
     */
    protected function initLocaleCache()
    {
        $availableLocales = Store::getInstance()->getLocales();

        $localeCollection = [];
        foreach ($availableLocales as $localeName) {
            $localeInfo = $this->getLocale($localeName);
            $localeCollection[$localeInfo->getLocaleName()] = $localeInfo;
        }

        $this->localeCache = new Collection($localeCollection);
    }

}

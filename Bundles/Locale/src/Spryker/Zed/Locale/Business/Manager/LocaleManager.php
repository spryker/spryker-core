<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Manager;

use Orm\Zed\Locale\Persistence\SpyLocale;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Locale\Business\Exception\LocaleExistsException;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Locale\Business\TransferGeneratorInterface;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;
use Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface;

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
     * @var \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface|null
     */
    protected $localeRepository;

    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     * @param \Spryker\Zed\Locale\Business\TransferGeneratorInterface $transferGenerator
     * @param \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface|null $localeRepository
     */
    public function __construct(
        LocaleQueryContainerInterface $localeQueryContainer,
        TransferGeneratorInterface $transferGenerator,
        ?LocaleRepositoryInterface $localeRepository = null
    ) {
        $this->localeQueryContainer = $localeQueryContainer;
        $this->transferGenerator = $transferGenerator;
        $this->localeRepository = $localeRepository;
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
        $localeQuery = $this->localeQueryContainer->queryLocaleByName($localeName);
        $locale = $localeQuery->findOne();
        if (!$locale) {
            throw new MissingLocaleException(
                sprintf(
                    'Tried to retrieve locale %s, but it does not exist',
                    $localeName
                )
            );
        }

        return $this->transferGenerator->convertLocale($locale);
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
        $locales = $this->getLocaleCollection();

        if (!array_key_exists($localeCode, $locales)) {
            throw new MissingLocaleException(
                sprintf(
                    'Tried to retrieve locale with code %s, but it does not exist',
                    $localeCode
                )
            );
        }

        return $locales[$localeCode];
    }

    /**
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleById($idLocale)
    {
        $localeEntity = $this->localeQueryContainer
            ->queryLocales()
            ->filterByIdLocale($idLocale)
            ->findOne();

        if (!$localeEntity) {
            throw new MissingLocaleException(
                sprintf(
                    'Tried to retrieve locale with id %s, but it does not exist',
                    $idLocale
                )
            );
        }

        return $this->transferGenerator->convertLocale($localeEntity);
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\LocaleExistsException
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

        return $this->transferGenerator->convertLocale($locale);
    }

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName)
    {
        $localeQuery = $this->localeQueryContainer->queryLocaleByName($localeName);

        return $localeQuery->count() > 0;
    }

    /**
     * @param string $localeName
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

        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection()
    {
        $storeInstance = Store::getInstance();
        if ($this->localeRepository) {
            return $this->getAvailableLocaleCollection($storeInstance);
        }

        $availableLocales = $storeInstance->getLocales();
        $transferCollection = [];
        foreach ($availableLocales as $localeName) {
            $localeInfo = $this->getLocale($localeName);
            $transferCollection[$localeInfo->getLocaleName()] = $localeInfo;
        }

        return $transferCollection;
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        $availableLocales = $this->getAvailableLocaleNames();
        $locales = [];
        foreach ($availableLocales as $localeName) {
            $localeInfo = $this->getLocale($localeName);
            $locales[$localeInfo->getIdLocale()] = $localeInfo->getLocaleName();
        }

        return $locales;
    }

    /**
     * @return array
     */
    public function getSupportedLocaleCodes(): array
    {
        $supportedLocaleCodes = [];
        $supportedStores = Store::getInstance()->getAllowedStores();

        foreach ($supportedStores as $supportedStore) {
            $supportedLocalesPerStore = Store::getInstance()->getLocalesPerStore($supportedStore);
            $supportedLocaleCodes = array_merge($supportedLocaleCodes, array_values($supportedLocalesPerStore));
        }

        return array_unique($supportedLocaleCodes);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocaleCollection(Store $store): array
    {
        $availableLocales = $store->getLocales();

        $localeTransfers = $this->localeRepository->getLocaleTransfersByLocaleNames($availableLocales);

        $indexedLocaleTransfers = $this->indexLocaleTransfersByLocalename($localeTransfers);

        return array_merge(array_flip($availableLocales), $indexedLocaleTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function indexLocaleTransfersByLocaleName(array $localeTransfers): array
    {
        $indexedLocaleTransfers = [];

        foreach ($localeTransfers as $localeTransfer) {
            $indexedLocaleTransfers[$localeTransfer->getLocaleName()] = $localeTransfer;
        }

        return $indexedLocaleTransfers;
    }
}

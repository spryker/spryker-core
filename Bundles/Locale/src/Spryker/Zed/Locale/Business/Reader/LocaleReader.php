<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Reader;

use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Locale\Business\Cache\LocaleCacheInterface;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface;
use Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface;

class LocaleReader implements LocaleReaderInterface
{
    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface
     */
    protected $localeRepository;

    /**
     * @var \Spryker\Zed\Locale\Business\Cache\LocaleCacheInterface
     */
    protected $localeCache;

    /**
     * @var \Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface
     */
    protected LocaleToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface $localeRepository
     * @param \Spryker\Zed\Locale\Business\Cache\LocaleCacheInterface $localeCache
     * @param \Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface $storeFacade
     */
    public function __construct(LocaleRepositoryInterface $localeRepository, LocaleCacheInterface $localeCache, LocaleToStoreFacadeInterface $storeFacade)
    {
        $this->localeRepository = $localeRepository;
        $this->localeCache = $localeCache;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleByName(string $localeName): LocaleTransfer
    {
        $localeTransfer = $this->localeCache->findByName($localeName);
        if ($localeTransfer) {
            return $localeTransfer;
        }

        $localeTransfer = $this->localeRepository->findLocaleTransferByLocaleName($localeName);

        if ($localeTransfer) {
            $this->localeCache->set($localeTransfer);

            return $localeTransfer;
        }

        throw new MissingLocaleException(
            sprintf(
                'Tried to retrieve locale %s, but it does not exist',
                $localeName,
            ),
        );
    }

    /**
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleById(int $idLocale): LocaleTransfer
    {
        $localeTransfer = $this->localeCache->findById($idLocale);
        if ($localeTransfer) {
            return $localeTransfer;
        }

        $localeTransfer = $this->localeRepository->findLocaleByIdLocale($idLocale);

        if ($localeTransfer) {
            $this->localeCache->set($localeTransfer);

            return $localeTransfer;
        }

        throw new MissingLocaleException(
            sprintf(
                'Tried to retrieve locale with id %s, but it does not exist',
                $idLocale,
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCriteriaTransfer|null $localeCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollection(?LocaleCriteriaTransfer $localeCriteriaTransfer = null): array
    {
        $indexedLocaleTransfers = [];

        foreach ($this->getLocaleTransfers($localeCriteriaTransfer) as $localeTransfer) {
            $indexedLocaleTransfers[$localeTransfer->getLocaleNameOrFail()] = $localeTransfer;
        }

        return $indexedLocaleTransfers;
    }

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function localeExists(string $localeName): bool
    {
        return $this->localeRepository->getLocalesCountByLocaleName($localeName) > 0;
    }

    /**
     * @return array<string>
     */
    public function getAvailableLocales(): array
    {
        $locales = [];
        foreach ($this->getLocaleTransfers() as $localeTransfer) {
            $locales[$localeTransfer->getIdLocale()] = $localeTransfer->getLocaleNameOrFail();
        }

        return $locales;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCriteriaTransfer|null $localeCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function getLocaleTransfers(?LocaleCriteriaTransfer $localeCriteriaTransfer = null): array
    {
        /* Required by infrastructure, exists only for BC reasons with DMS mode. */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            $availableLocales = Store::getInstance()->getLocales();

            return $this->localeRepository->getLocaleTransfersByLocaleNames($availableLocales);
        }

        if (!$localeCriteriaTransfer) {
            $localeCriteriaTransfer = (new LocaleCriteriaTransfer())
                ->setLocaleConditions(
                    (new LocaleConditionsTransfer())
                        ->setAssignedToStore(true),
                );
        }

        return $this->localeRepository->getLocaleCollectionByCriteria($localeCriteriaTransfer);
    }
}

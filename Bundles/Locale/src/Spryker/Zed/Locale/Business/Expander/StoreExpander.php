<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Expander;

use Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface;
use Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface;

class StoreExpander implements StoreExpanderInterface
{
    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface
     */
    protected LocaleRepositoryInterface $localeRepository;

    /**
     * @var \Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface
     */
    protected LocaleToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface $localeRepository
     * @param \Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface $storeFacade
     */
    public function __construct(LocaleRepositoryInterface $localeRepository, LocaleToStoreFacadeInterface $storeFacade)
    {
        $this->localeRepository = $localeRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithLocales(array $storeTransfers): array
    {
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $storeTransfers;
        }

        $storeIds = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStoreOrFail();
        }

        $localeNamesGroupedByIdStore = $this->localeRepository->getLocaleNamesGroupedByIdStore($storeIds);

        $defaultLocaleNamesIndexedByIdStore = $this->localeRepository->getDefaultLocaleNamesIndexedByIdStore($storeIds);

        foreach ($storeTransfers as $storeTransfer) {
            $storeTransfer
                ->setDefaultLocaleIsoCode($defaultLocaleNamesIndexedByIdStore[$storeTransfer->getIdStoreOrFail()])
                ->setAvailableLocaleIsoCodes($localeNamesGroupedByIdStore[$storeTransfer->getIdStoreOrFail()] ?? []);
        }

        return $storeTransfers;
    }
}

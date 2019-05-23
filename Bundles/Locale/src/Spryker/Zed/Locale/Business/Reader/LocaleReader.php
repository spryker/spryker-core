<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Reader;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface;

class LocaleReader implements LocaleReaderInterface
{
    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface
     */
    protected $localeRepository;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface $localeRepository
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(LocaleRepositoryInterface $localeRepository, Store $store)
    {
        $this->localeRepository = $localeRepository;
        $this->store = $store;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function findAvailableLocaleCollection(): array
    {
        $availableLocales = $this->store->getLocales();

        $localeTransfers = $this->localeRepository->findLocaleByLocaleNames($availableLocales);

        return $this->indexLocaleTransfersByLocalename($localeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return array
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

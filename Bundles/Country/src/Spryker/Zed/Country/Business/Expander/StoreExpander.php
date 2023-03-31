<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Expander;

use Spryker\Zed\Country\Dependency\Facade\CountryToStoreFacadeInterface;
use Spryker\Zed\Country\Persistence\CountryRepositoryInterface;

class StoreExpander implements StoreExpanderInterface
{
    /**
     * @var string
     */
    protected const KEY_CODES = 'codes';

    /**
     * @var string
     */
    protected const KEY_NAMES = 'names';

    /**
     * @var \Spryker\Zed\Country\Persistence\CountryRepositoryInterface
     */
    protected CountryRepositoryInterface $countryRepository;

    /**
     * @var \Spryker\Zed\Country\Dependency\Facade\CountryToStoreFacadeInterface
     */
    protected CountryToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryRepositoryInterface $countryRepository
     * @param \Spryker\Zed\Country\Dependency\Facade\CountryToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CountryRepositoryInterface $countryRepository,
        CountryToStoreFacadeInterface $storeFacade
    ) {
        $this->countryRepository = $countryRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithCountries(array $storeTransfers): array
    {
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $storeTransfers;
        }

        $storeIds = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStoreOrFail();
        }

        $countryDataGroupedByIdStore = $this->countryRepository->getCountryDataGroupedByIdStore($storeIds);

        foreach ($storeTransfers as $storeTransfer) {
            $countryData = $countryDataGroupedByIdStore[$storeTransfer->getIdStoreOrFail()] ?? [];
            $countryNames = $countryData[static::KEY_NAMES] ?? [];
            $countryCodes = $countryData[static::KEY_CODES] ?? [];

            $storeTransfer
                ->setCountryNames($countryNames)
                ->setCountries($countryCodes);
        }

        return $storeTransfers;
    }
}

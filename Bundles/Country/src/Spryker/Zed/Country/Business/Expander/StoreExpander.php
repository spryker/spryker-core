<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Expander;

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
     * @param \Spryker\Zed\Country\Persistence\CountryRepositoryInterface $countryRepository
     */
    public function __construct(
        CountryRepositoryInterface $countryRepository
    ) {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithCountries(array $storeTransfers): array
    {
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

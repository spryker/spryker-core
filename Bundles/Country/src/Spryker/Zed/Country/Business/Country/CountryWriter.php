<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Country;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Country\Business\Exception\CountryExistsException;
use Spryker\Zed\Country\Dependency\Facade\CountryToStoreFacadeInterface;
use Spryker\Zed\Country\Persistence\CountryEntityManagerInterface;

class CountryWriter implements CountryWriterInterface
{
    /**
     * @var \Spryker\Zed\Country\Persistence\CountryEntityManagerInterface
     */
    protected CountryEntityManagerInterface $entityManager;

    /**
     * @var \Spryker\Zed\Country\Business\Country\CountryReaderInterface
     */
    protected CountryReaderInterface $countryReader;

    /**
     * @var \Spryker\Zed\Country\Dependency\Facade\CountryToStoreFacadeInterface
     */
    protected CountryToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Country\Business\Country\CountryReaderInterface $countryReader
     * @param \Spryker\Zed\Country\Dependency\Facade\CountryToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CountryEntityManagerInterface $entityManager,
        CountryReaderInterface $countryReader,
        CountryToStoreFacadeInterface $storeFacade
    ) {
        $this->entityManager = $entityManager;
        $this->countryReader = $countryReader;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function createCountry(CountryTransfer $countryTransfer): CountryTransfer
    {
        $this->assertCountryDoesNotExist($countryTransfer->getIso2CodeOrFail());

        return $this->entityManager->createCountry($countryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreCountries(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $this->getSuccessfulResponse($storeTransfer);
        }

        $countryCollectionTransfer = $this->countryReader->getCountriesByIso2Codes($storeTransfer->getCountries());

        $countryIds = [];

        foreach ($countryCollectionTransfer->getCountries() as $countryTransfer) {
            $countryIds[] = $countryTransfer->getIdCountryOrFail();
        }

        $this->entityManager->updateStoresCountries($storeTransfer->getIdStoreOrFail(), $countryIds);

        return $this->getSuccessfulResponse($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function getSuccessfulResponse(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return (new StoreResponseTransfer())
            ->setStore($storeTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param string $iso2code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\CountryExistsException
     *
     * @return void
     */
    protected function assertCountryDoesNotExist(string $iso2code): void
    {
        if ($this->countryReader->countryExists($iso2code)) {
            throw new CountryExistsException();
        }
    }
}

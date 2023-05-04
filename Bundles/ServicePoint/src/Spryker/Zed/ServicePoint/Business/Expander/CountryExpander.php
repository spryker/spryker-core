<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryConditionsTransfer;
use Generated\Shared\Transfer\CountryCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToCountryFacadeInterface;

class CountryExpander implements CountryExpanderInterface
{
    /**
     * @var \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToCountryFacadeInterface
     */
    protected ServicePointToCountryFacadeInterface $countryFacade;

    /**
     * @param \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToCountryFacadeInterface $countryFacade
     */
    public function __construct(ServicePointToCountryFacadeInterface $countryFacade)
    {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer
     */
    public function expandServicePointAddressCollectionWithCountriesAndRegions(
        ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
    ): ServicePointAddressCollectionTransfer {
        $countryIso2Codes = $this->extractCountryIso2CodesFromServicePointAddressCollection($servicePointAddressCollectionTransfer);
        $countryConditionsTransfer = (new CountryConditionsTransfer())
            ->setIso2Codes($countryIso2Codes)
            ->setWithRegions(true);
        $countryCollectionTransfer = $this->countryFacade->getCountryCollection(
            (new CountryCriteriaTransfer())->setCountryConditions($countryConditionsTransfer),
        );

        $countryTransfersIndexedByIso2Code = $this->getCountriesIndexedByIso2Code($countryCollectionTransfer);
        $regionTransfersIndexedByUuid = $this->getRegionsIndexedByUuid($countryCollectionTransfer);

        foreach ($servicePointAddressCollectionTransfer->getServicePointAddresses() as $servicePointAddressTransfer) {
            $servicePointAddressTransfer->setCountry(
                $countryTransfersIndexedByIso2Code[$servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail()],
            );

            if ($servicePointAddressTransfer->getRegion()) {
                $servicePointAddressTransfer->setRegion(
                    $regionTransfersIndexedByUuid[$servicePointAddressTransfer->getRegionOrFail()->getUuidOrFail()],
                );
            }
        }

        return $servicePointAddressCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\RegionTransfer>
     */
    protected function getRegionsIndexedByUuid(CountryCollectionTransfer $countryCollectionTransfer): array
    {
        $regionTransfersIndexedByUuid = [];

        foreach ($countryCollectionTransfer->getCountries() as $countryTransfer) {
            foreach ($countryTransfer->getRegions() as $regionTransfer) {
                $regionTransfersIndexedByUuid[$regionTransfer->getUuidOrFail()] = $regionTransfer;
            }
        }

        return $regionTransfersIndexedByUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\CountryTransfer>
     */
    protected function getCountriesIndexedByIso2Code(CountryCollectionTransfer $countryCollectionTransfer): array
    {
        $countryTransfersIndexedByIso2Code = [];

        foreach ($countryCollectionTransfer->getCountries() as $countryTransfer) {
            $countryTransfersIndexedByIso2Code[$countryTransfer->getIso2CodeOrFail()] = $countryTransfer;
        }

        return $countryTransfersIndexedByIso2Code;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractCountryIso2CodesFromServicePointAddressCollection(
        ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
    ): array {
        $countryIso2Codes = [];

        foreach ($servicePointAddressCollectionTransfer->getServicePointAddresses() as $servicePointAddressTransfer) {
            $countryIso2Codes[] = $servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail();
        }

        return $countryIso2Codes;
    }
}

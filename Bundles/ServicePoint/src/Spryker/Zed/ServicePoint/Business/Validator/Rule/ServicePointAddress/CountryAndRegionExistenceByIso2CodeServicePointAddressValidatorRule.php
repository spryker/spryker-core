<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress;

use ArrayObject;
use Generated\Shared\Transfer\CountryConditionsTransfer;
use Generated\Shared\Transfer\CountryCriteriaTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToCountryFacadeInterface;

class CountryAndRegionExistenceByIso2CodeServicePointAddressValidatorRule implements ServicePointAddressValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_REGION_ENTITY_NOT_FOUND = 'service_point.validation.region_entity_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_COUNTRY_ENTITY_NOT_FOUND = 'service_point.validation.country_entity_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_ISO2_CODE = '%iso2Code%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_UUID = '%uuid%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_COUNTRY_ISO2_CODE = '%countryIso2Code%';

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToCountryFacadeInterface
     */
    protected ServicePointToCountryFacadeInterface $countryFacade;

    /**
     * @param \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToCountryFacadeInterface $countryFacade
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        ServicePointToCountryFacadeInterface $countryFacade,
        ErrorAdderInterface $errorAdder
    ) {
        $this->countryFacade = $countryFacade;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $servicePointAddressTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($servicePointAddressTransfers as $entityIdentifier => $servicePointAddressTransfer) {
            $countryTransfer = $this->findCountryByIso2Code($servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail());

            if (!$countryTransfer) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_COUNTRY_ENTITY_NOT_FOUND,
                    [
                        static::GLOSSARY_KEY_PARAMETER_ISO2_CODE => $servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
                    ],
                );

                continue;
            }

            if (!$servicePointAddressTransfer->getRegion()) {
                continue;
            }

            if (!$this->hasRegionByCountry($countryTransfer, $servicePointAddressTransfer->getRegionOrFail())) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_REGION_ENTITY_NOT_FOUND,
                    [
                        static::GLOSSARY_KEY_PARAMETER_UUID => $servicePointAddressTransfer->getRegionOrFail()->getUuidOrFail(),
                        static::GLOSSARY_KEY_PARAMETER_COUNTRY_ISO2_CODE => $servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
                    ],
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    public function isTerminated(
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     * @param \Generated\Shared\Transfer\RegionTransfer $regionTransfer
     *
     * @return bool
     */
    protected function hasRegionByCountry(CountryTransfer $countryTransfer, RegionTransfer $regionTransfer): bool
    {
        $expectedUuid = $regionTransfer->getUuidOrFail();

        foreach ($countryTransfer->getRegions() as $regionTransfer) {
            if ($regionTransfer->getUuidOrFail() === $expectedUuid) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $countryIso2Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    protected function findCountryByIso2Code(string $countryIso2Code): ?CountryTransfer
    {
        $countryConditionsTransfer = (new CountryConditionsTransfer())
            ->addIso2Code($countryIso2Code)
            ->setWithRegions(true);

        $countryCriteriaTransfer = (new CountryCriteriaTransfer())
            ->setCountryConditions($countryConditionsTransfer);

        $countryCollectionTransfer = $this->countryFacade->getCountryCollection($countryCriteriaTransfer);

        return $countryCollectionTransfer->getCountries()->getIterator()->current();
    }
}

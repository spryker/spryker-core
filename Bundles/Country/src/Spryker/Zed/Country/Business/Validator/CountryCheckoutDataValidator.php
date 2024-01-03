<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Country\Business\Country\CountryReaderInterface;
use Spryker\Zed\Country\Persistence\CountryRepositoryInterface;

class CountryCheckoutDataValidator implements CountryCheckoutDataValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_BILLING_ADDRESS_IS_MISSING = 'billing.address.validation.is_missing';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_BILLING_ADDRESS_COUNTRY_NOT_FOUND = 'billing.address.country.validation.not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SHIPPING_ADDRESS_COUNTRY_NOT_FOUND = 'shipping.address.country.validation.not_found';

    /**
     * @var string
     */
    protected const COUNTRY_CODE_PARAMETER = '%code%';

    /**
     * @var \Spryker\Zed\Country\Business\Country\CountryReaderInterface
     */
    protected $countryReader;

    /**
     * @var \Spryker\Zed\Country\Persistence\CountryRepositoryInterface
     */
    protected $countryRepository;

    /**
     * @param \Spryker\Zed\Country\Business\Country\CountryReaderInterface $countryReader
     * @param \Spryker\Zed\Country\Persistence\CountryRepositoryInterface $countryRepository
     */
    public function __construct(
        CountryReaderInterface $countryReader,
        CountryRepositoryInterface $countryRepository
    ) {
        $this->countryReader = $countryReader;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidator::validateCountriesInCheckoutData()} instead.
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCountryCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $checkoutResponseTransfer = $this->validateCountryInBillingAddress($checkoutDataTransfer, $checkoutResponseTransfer);

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $checkoutResponseTransfer;
        }

        return $this->validateCountriesInShipments($checkoutDataTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCountriesInCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $checkoutResponseTransfer = $this->checkCountryInBillingAddress($checkoutDataTransfer, $checkoutResponseTransfer);

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $checkoutResponseTransfer;
        }

        return $this->validateCountriesInShipments($checkoutDataTransfer, $checkoutResponseTransfer);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidator::checkCountryInBillingAddress()} instead.
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function validateCountryInBillingAddress(
        CheckoutDataTransfer $checkoutDataTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer {
        if (!$checkoutDataTransfer->getBillingAddress()) {
            return $this->addErrorToCheckoutResponseTransfer(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_BILLING_ADDRESS_IS_MISSING,
            );
        }

        $billingAddressCountryIso2Code = $checkoutDataTransfer->getBillingAddressOrFail()->getIso2Code();

        if ($billingAddressCountryIso2Code === null || !$this->countryReader->countryExists($billingAddressCountryIso2Code)) {
            $checkoutResponseTransfer = $this->addErrorToCheckoutResponseTransfer(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_BILLING_ADDRESS_COUNTRY_NOT_FOUND,
                [static::COUNTRY_CODE_PARAMETER => $billingAddressCountryIso2Code],
            );
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function validateCountriesInShipments(
        CheckoutDataTransfer $checkoutDataTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer {
        if (!count($checkoutDataTransfer->getShipments())) {
            return $checkoutResponseTransfer;
        }

        $shipmentIso2Codes = $this->extractIso2CodesFromShipments($checkoutDataTransfer);
        $countryIso2Codes = $this->getCountriesIsoCodes($shipmentIso2Codes);

        foreach ($shipmentIso2Codes as $shipmentIso2Code) {
            if (!in_array($shipmentIso2Code, $countryIso2Codes, true)) {
                $checkoutResponseTransfer = $this->addErrorToCheckoutResponseTransfer(
                    $checkoutResponseTransfer,
                    static::GLOSSARY_KEY_SHIPPING_ADDRESS_COUNTRY_NOT_FOUND,
                    [static::COUNTRY_CODE_PARAMETER => $shipmentIso2Code],
                );
            }
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return array<string>
     */
    protected function extractIso2CodesFromShipments(CheckoutDataTransfer $checkoutDataTransfer): array
    {
        $iso2Codes = [];

        foreach ($checkoutDataTransfer->getShipments() as $restShipmentsTransfer) {
            $restAddressTransfer = $restShipmentsTransfer->getShippingAddress();
            if ($restAddressTransfer !== null && $restAddressTransfer->getIso2Code()) {
                $iso2Codes[] = $restAddressTransfer->getIso2CodeOrFail();
            }
        }

        return $iso2Codes;
    }

    /**
     * @param array<string> $iso2Codes
     *
     * @return array<string>
     */
    protected function getCountriesIsoCodes(array $iso2Codes): array
    {
        $countryTransfers = $this->countryRepository
            ->getCountriesByIso2Codes($iso2Codes)
            ->getCountries();

        $countryIso2Codes = [];

        foreach ($countryTransfers as $countryTransfer) {
            $countryIso2Codes[] = $countryTransfer->getIso2CodeOrFail();
        }

        return $countryIso2Codes;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     * @param array<mixed> $parameters
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addErrorToCheckoutResponseTransfer(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        string $message,
        array $parameters = []
    ): CheckoutResponseTransfer {
        return $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError((new CheckoutErrorTransfer())
                ->setParameters($parameters)
                ->setMessage($message));
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function checkCountryInBillingAddress(
        CheckoutDataTransfer $checkoutDataTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer {
        if (!$checkoutDataTransfer->getBillingAddress()) {
            return $this->addErrorToCheckoutResponseTransfer(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_BILLING_ADDRESS_IS_MISSING,
            );
        }

        $billingAddressCountryIso2Code = $checkoutDataTransfer->getBillingAddressOrFail()->getIso2Code();
        if (!$billingAddressCountryIso2Code) {
            return $checkoutResponseTransfer;
        }

        if (!$this->countryReader->countryExists($billingAddressCountryIso2Code)) {
            $checkoutResponseTransfer = $this->addErrorToCheckoutResponseTransfer(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_BILLING_ADDRESS_COUNTRY_NOT_FOUND,
                [static::COUNTRY_CODE_PARAMETER => $billingAddressCountryIso2Code],
            );
        }

        return $checkoutResponseTransfer;
    }
}

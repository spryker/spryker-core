<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Persistence\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;

class CustomerMapper implements CustomerMapperInterface
{
    /**
     * @param array $customer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapCustomerEntityToCustomer(array $customer): CustomerTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->fromArray(
                $customer,
                true
            );

        return $customerTransfer;
    }

    /**
     * @deprecated Use mapCustomerAddressEntityToAddressTransfer() instead.
     *
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerAddress $customerAddressEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapCustomerAddressEntityToTransfer(SpyCustomerAddress $customerAddressEntity): AddressTransfer
    {
        return (new AddressTransfer())->fromArray($customerAddressEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerAddress $customerAddressEntity
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapCustomerAddressEntityToAddressTransfer(
        SpyCustomerAddress $customerAddressEntity,
        AddressTransfer $addressTransfer
    ): AddressTransfer {
        $addressTransfer = $addressTransfer->fromArray($customerAddressEntity->toArray(), true);

        $countryEntity = $customerAddressEntity->getCountry();
        if ($countryEntity === null) {
            return $addressTransfer;
        }

        $countryTransfer = $this->mapCountryEntityToCountryTransfer($countryEntity, new CountryTransfer());

        $addressTransfer->setCountry($countryTransfer);
        $addressTransfer->setIso2Code($countryTransfer->getIso2Code());

        return $addressTransfer;
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function mapCountryEntityToCountryTransfer(SpyCountry $countryEntity, CountryTransfer $countryTransfer): CountryTransfer
    {
        return $countryTransfer->fromArray($countryEntity->toArray(), true);
    }
}

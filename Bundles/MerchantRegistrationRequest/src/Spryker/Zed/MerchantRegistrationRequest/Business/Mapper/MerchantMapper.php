<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Mapper;

use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;

class MerchantMapper implements MerchantMapperInterface
{
    public function __construct(
        protected MerchantRegistrationRequestConfig $merchantRegistrationRequestConfig
    ) {
    }

    public function mapMerchantRegistrationRequestTransferToMerchantTransfer(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        return $merchantTransfer->setName($merchantRegistrationRequestTransfer->getCompanyName())
            ->setEmail($merchantRegistrationRequestTransfer->getEmail())
            ->setIsActive(false)
            ->setStatus($this->merchantRegistrationRequestConfig->getDefaultMerchantStatus())
            ->setRegistrationNumber($merchantRegistrationRequestTransfer->getRegistrationNumber())
            ->setMerchantProfile($this->mapMerchantRegistrationRequestTransferToMerchantProfileTransfer($merchantRegistrationRequestTransfer, new MerchantProfileTransfer()))
            ->setStoreRelation($this->mapMerchantRegistrationRequestTransferToStoreRelationTransfer($merchantRegistrationRequestTransfer, new StoreRelationTransfer()));
    }

    protected function mapMerchantRegistrationRequestTransferToMerchantProfileTransfer(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        return $merchantProfileTransfer->setContactPersonTitle($merchantRegistrationRequestTransfer->getContactPersonTitle())
            ->setContactPersonFirstName($merchantRegistrationRequestTransfer->getContactPersonFirstName())
            ->setContactPersonLastName($merchantRegistrationRequestTransfer->getContactPersonLastName())
            ->setContactPersonPhone($merchantRegistrationRequestTransfer->getContactPersonPhone())
            ->setContactPersonRole($merchantRegistrationRequestTransfer->getContactPersonRole())
            ->addAddress($this->mapMerchantRegistrationRequestTransferToMerchantProfileAddressTransfer($merchantRegistrationRequestTransfer, new MerchantProfileAddressTransfer()));
    }

    protected function mapMerchantRegistrationRequestTransferToMerchantProfileAddressTransfer(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer
    ): MerchantProfileAddressTransfer {
        return $merchantProfileAddressTransfer->setAddress1($merchantRegistrationRequestTransfer->getAddress1())
            ->setAddress2($merchantRegistrationRequestTransfer->getAddress2())
            ->setCity($merchantRegistrationRequestTransfer->getCity())
            ->setZipCode($merchantRegistrationRequestTransfer->getZipCode())
            ->setFkCountry($merchantRegistrationRequestTransfer->getCountryOrFail()->getIdCountryOrFail())
            ->setCountryName($merchantRegistrationRequestTransfer->getCountryOrFail()->getIso2CodeOrFail())
            ->setEmail($merchantRegistrationRequestTransfer->getEmail());
    }

    protected function mapMerchantRegistrationRequestTransferToStoreRelationTransfer(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        return $storeRelationTransfer->addStores(
            $merchantRegistrationRequestTransfer->getStoreOrFail(),
        );
    }
}

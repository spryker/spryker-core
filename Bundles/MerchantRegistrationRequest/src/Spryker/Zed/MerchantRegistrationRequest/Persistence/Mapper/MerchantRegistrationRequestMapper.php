<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Persistence\Mapper;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantRegistrationRequest\Persistence\SpyMerchantRegistrationRequest;

class MerchantRegistrationRequestMapper
{
    public function mapMerchantRegistrationRequestTransferToMerchantRegistrationRequestEntity(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        ?SpyMerchantRegistrationRequest $merchantRegistrationRequestEntity = null
    ): SpyMerchantRegistrationRequest {
        $merchantRegistrationRequestEntity = $merchantRegistrationRequestEntity ?? new SpyMerchantRegistrationRequest();

        $merchantRegistrationRequestEntity->fromArray($merchantRegistrationRequestTransfer->modifiedToArray())
            ->setFkCountry($merchantRegistrationRequestTransfer->getCountryOrFail()->getIdCountryOrFail())
            ->setFkStore($merchantRegistrationRequestTransfer->getStoreOrFail()->getIdStoreOrFail());

        return $merchantRegistrationRequestEntity;
    }

    public function mapMerchantRegistrationRequestEntityToMerchantRegistrationRequestTransfer(
        SpyMerchantRegistrationRequest $merchantRegistrationRequestEntity,
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer {
        $merchantRegistrationRequestTransfer->fromArray($merchantRegistrationRequestEntity->toArray(), true);
        $merchantRegistrationRequestTransfer->setCountry((new CountryTransfer())->fromArray($merchantRegistrationRequestEntity->getCountry()->toArray(), true));
        $merchantRegistrationRequestTransfer->setStore((new StoreTransfer())->fromArray($merchantRegistrationRequestEntity->getStore()->toArray(), true));

        return $merchantRegistrationRequestTransfer;
    }
}

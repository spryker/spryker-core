<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Generator;

use Generated\Shared\Transfer\IdGeneratorSettingsTransfer;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestToUtilUuidGeneratorServiceInterface;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;

class UniqueRandomIdMerchantReferenceGenerator implements MerchantReferenceGeneratorInterface
{
    public function __construct(
        protected MerchantRegistrationRequestToUtilUuidGeneratorServiceInterface $utilUuidGeneratorService,
        protected MerchantRegistrationRequestConfig $merchantRegistrationRequestConfig
    ) {
    }

    public function generateMerchantReference(): string
    {
        $idGeneratorSettingsTransfer = (new IdGeneratorSettingsTransfer())
            ->setAlphabet($this->merchantRegistrationRequestConfig->getUniqueRandomIdMerchantReferenceAlphabet())
            ->setSize($this->merchantRegistrationRequestConfig->getUniqueRandomIdMerchantReferenceSize());

        return $this->merchantRegistrationRequestConfig->getMerchantReferencePrefix() . $this->utilUuidGeneratorService
                ->generateUniqueRandomId($idGeneratorSettingsTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Creator;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToCountryFacadeInterface;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;
use Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestEntityManagerInterface;

class MerchantRegistrationRequestCreator implements MerchantRegistrationRequestCreatorInterface
{
    /**
     * @param list<\Spryker\Zed\MerchantRegistrationRequest\Business\Validator\MerchantRegistrationRequestValidatorInterface> $merchantRegistrationRequestValidators
     */
    public function __construct(
        protected MerchantRegistrationRequestEntityManagerInterface $entityManager,
        protected MerchantRegistrationRequestToCountryFacadeInterface $countryFacade,
        protected MerchantRegistrationRequestConfig $merchantRegistrationRequestConfig,
        protected array $merchantRegistrationRequestValidators
    ) {
    }

    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        $merchantRegistrationResponseTransfer = (new MerchantRegistrationResponseTransfer())
            ->setIsSuccess(true);
        $merchantRegistrationResponseTransfer = $this->executeMerchantRegistrationRequestValidators(
            $merchantRegistrationRequestTransfer,
            $merchantRegistrationResponseTransfer,
        );

        if (!$merchantRegistrationResponseTransfer->getIsSuccess()) {
            return $merchantRegistrationResponseTransfer;
        }

        if (!$merchantRegistrationRequestTransfer->getStatus()) {
            $merchantRegistrationRequestTransfer->setStatus($this->merchantRegistrationRequestConfig->getDefaultMerchantRegistrationRequestStatus());
        }

        $merchantRegistrationRequestTransfer->setCountry(
            $this->countryFacade->getCountryByIso2Code($merchantRegistrationRequestTransfer->getIso2CodeOrFail()),
        );
        $createdMerchantRegistrationRequestTransfer = $this->entityManager->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);

        return $merchantRegistrationResponseTransfer->setMerchantRegistrationRequest($createdMerchantRegistrationRequestTransfer);
    }

    protected function executeMerchantRegistrationRequestValidators(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        MerchantRegistrationResponseTransfer $merchantRegistrationResponseTransfer
    ): MerchantRegistrationResponseTransfer {
        foreach ($this->merchantRegistrationRequestValidators as $merchantRegistrationRequestValidator) {
            $merchantRegistrationResponseTransfer = $merchantRegistrationRequestValidator
                ->validateMerchantRegistrationRequest($merchantRegistrationRequestTransfer, $merchantRegistrationResponseTransfer);
        }

        return $merchantRegistrationResponseTransfer;
    }
}

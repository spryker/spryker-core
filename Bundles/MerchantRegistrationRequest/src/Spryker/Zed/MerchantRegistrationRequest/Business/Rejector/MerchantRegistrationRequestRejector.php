<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Rejector;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;
use Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestEntityManagerInterface;

class MerchantRegistrationRequestRejector implements MerchantRegistrationRequestRejectorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_CANNOT_BE_REJECTED = 'merchant_registration_request.error.merchant_cannot_be_rejected';

    public function __construct(
        protected MerchantRegistrationRequestEntityManagerInterface $merchantRegistrationRequestEntityManager,
        protected MerchantRegistrationRequestConfig $merchantRegistrationRequestConfig
    ) {
    }

    public function rejectMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        $merchantRegistrationResponseTransfer = (new MerchantRegistrationResponseTransfer())
            ->setIsSuccess(true);

        if (!in_array($merchantRegistrationRequestTransfer->getStatus(), $this->merchantRegistrationRequestConfig->getRejectableStatuses())) {
            return $merchantRegistrationResponseTransfer->setIsSuccess(false)
                ->addError((new ErrorTransfer())->setMessage(static::GLOSSARY_KEY_MERCHANT_CANNOT_BE_REJECTED))
                ->setMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
        }

        $merchantRegistrationRequestTransfer->setStatus(MerchantRegistrationRequestConfig::STATUS_REJECTED);
        $merchantRegistrationRequestTransfer = $this->merchantRegistrationRequestEntityManager
            ->updateMerchantRegistrationRequest($merchantRegistrationRequestTransfer);

        return $merchantRegistrationResponseTransfer->setMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }
}

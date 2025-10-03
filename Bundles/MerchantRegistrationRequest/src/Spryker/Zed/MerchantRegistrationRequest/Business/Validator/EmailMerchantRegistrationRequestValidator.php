<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Validator;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;
use Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface;

class EmailMerchantRegistrationRequestValidator implements MerchantRegistrationRequestValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_EMAIL_ALREARY_EXISTS = 'merchant_registration_request.error.email_already_exists';

    public function __construct(protected MerchantRegistrationRequestRepositoryInterface $repository)
    {
    }

    public function validateMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        MerchantRegistrationResponseTransfer $merchantRegistrationResponseTransfer
    ): MerchantRegistrationResponseTransfer {
        if ($this->repository->isEmailAlreadyInUse($merchantRegistrationRequestTransfer->getEmailOrFail())) {
            $merchantRegistrationResponseTransfer->setIsSuccess(false)
                ->addError((new ErrorTransfer())->setMessage(static::GLOSSARY_KEY_EMAIL_ALREARY_EXISTS));
        }

        return $merchantRegistrationResponseTransfer;
    }
}

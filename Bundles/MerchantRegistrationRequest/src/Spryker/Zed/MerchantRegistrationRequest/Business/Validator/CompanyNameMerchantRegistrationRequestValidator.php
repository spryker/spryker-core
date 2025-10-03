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

class CompanyNameMerchantRegistrationRequestValidator implements MerchantRegistrationRequestValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_NAME_ALREADY_EXISTS = 'merchant_registration_request.error.company_name_already_exists';

    public function __construct(protected MerchantRegistrationRequestRepositoryInterface $repository)
    {
    }

    public function validateMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        MerchantRegistrationResponseTransfer $merchantRegistrationResponseTransfer
    ): MerchantRegistrationResponseTransfer {
        if ($this->repository->isCompanyNameAlreadyInUse($merchantRegistrationRequestTransfer->getCompanyNameOrFail())) {
            $merchantRegistrationResponseTransfer->setIsSuccess(false)
                ->addError((new ErrorTransfer())->setMessage(static::GLOSSARY_KEY_COMPANY_NAME_ALREADY_EXISTS));
        }

        return $merchantRegistrationResponseTransfer;
    }
}

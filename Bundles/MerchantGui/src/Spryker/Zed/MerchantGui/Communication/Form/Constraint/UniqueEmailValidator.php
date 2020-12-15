<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
{
    protected const ERROR_MESSAGE_PROVIDED_EMAIL_IS_ALREADY_USED = 'Email is already used.';

    /**
     * Checks if the passed email is unique.
     *
     * @param string|null $email
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($email, Constraint $constraint)
    {
        if (!$email) {
            return;
        }

        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
        $merchantCriteriaTransfer->setEmail($email);
        $merchantTransfer = $constraint->getMerchantFacade()->findOne($merchantCriteriaTransfer);
        if ($merchantTransfer === null) {
            return;
        }

        if ($constraint->getCurrentIdMerchant() === $merchantTransfer->getIdMerchant()) {
            return;
        }

        $this->context->buildViolation(static::ERROR_MESSAGE_PROVIDED_EMAIL_IS_ALREADY_USED)
            ->atPath('merchant_email')
            ->addViolation();
    }
}

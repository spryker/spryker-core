<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
{
    protected const ERROR_MESSAGE_PROVIDED_EMAIL_IS_ALREADY_USED = 'Email is already used.';

    /**
     * Checks if the passed email is unique.
     *
     * @param string $email
     * @param \Symfony\Component\Validator\Constraint $uniqueEmailConstraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($email, Constraint $uniqueEmailConstraint): void
    {
        if (empty($email)) {
            return;
        }

        if (!$uniqueEmailConstraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($uniqueEmailConstraint, UniqueEmail::class);
        }

        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setEmail($email);
        $merchantTransfer = $uniqueEmailConstraint->getMerchantFacade()->findOne($merchantCriteriaFilterTransfer);
        if ($merchantTransfer === null) {
            return;
        }

        if ($uniqueEmailConstraint->getCurrentIdMerchant() === $merchantTransfer->getIdMerchant()) {
            return;
        }

        $this->context->buildViolation(static::ERROR_MESSAGE_PROVIDED_EMAIL_IS_ALREADY_USED)
            ->atPath('merchant_email')
            ->addViolation();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserEmailConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueUserEmailConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueUserEmailConstraint::class);
        }

        if (!$this->isUniqueUserEmail($value, $constraint)) {
            $this->context
                ->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }

    /**
     * @param string|null $email
     * @param \Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\UniqueUserEmailConstraint $constraint
     *
     * @return bool
     */
    protected function isUniqueUserEmail(?string $email, UniqueUserEmailConstraint $constraint): bool
    {
        if (empty($email)) {
            return false;
        }

        $userCriteriaTransfer = (new UserCriteriaTransfer())->setEmail($email);

        return !$constraint->getMerchantUserFacade()->findUser($userCriteriaTransfer);
    }
}

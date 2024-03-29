<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
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
        if (!$email) {
            return false;
        }

        $userTransfer = $this->findUserTransfer($constraint, $email);
        if ($userTransfer === null) {
            return true;
        }

        return $constraint->getIdUser() === $userTransfer->getIdUser();
    }

    /**
     * @param \Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\UniqueUserEmailConstraint $constraint
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserTransfer(UniqueUserEmailConstraint $constraint, string $email): ?UserTransfer
    {
        $userCriteriaTransfer = $this->createUserCriteriaTransfer($email);
        $userCollectionTransfer = $constraint->getMerchantUserFacade()->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(string $email): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())->addUsername($email);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }
}

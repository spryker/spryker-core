<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed|string $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\MerchantUserGui\Communication\Form\Constraint\UniqueEmailConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmailConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueEmailConstraint::class);
        }

        $userCriteriaTransfer = $this->createUserCriteriaTransfer($value);
        $userCollectionTransfer = $constraint->getMerchantUserFacade()->getUserCollection($userCriteriaTransfer);

        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        if (!$userTransfer) {
            return;
        }

        /** @var \Generated\Shared\Transfer\UserTransfer $formDataUserTransfer */
        $formDataUserTransfer = $this->context->getRoot()->getData();

        if ($userTransfer->getIdUser() !== $formDataUserTransfer->getIdUser()) {
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ username }}', $value)
                ->addViolation();
        }
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(string $username): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())->addUsername($username);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }
}

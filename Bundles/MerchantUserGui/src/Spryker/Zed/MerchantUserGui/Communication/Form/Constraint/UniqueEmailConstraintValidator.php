<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailConstraintValidator extends ConstraintValidator
{
    /**
     * @param string $email
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\MerchantUserGui\Communication\Form\Constraint\UniqueEmailConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($email, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmailConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueEmailConstraint::class);
        }

        if (!$constraint->getUserFacade()->hasUserByUsername($email)) {
            return;
        }

        /** @var \Generated\Shared\Transfer\UserTransfer $formDataUserTransfer */
        $formDataUserTransfer = $this->context->getRoot()->getData();
        if (!$formDataUserTransfer->getIdUser()) {
            $this->addViolation($constraint, $email);

            return;
        }

        $userTransfer = $constraint->getUserFacade()->getUserByUsername($email);
        if ($userTransfer->getIdUser() !== $formDataUserTransfer->getIdUser()) {
            $this->addViolation($constraint, $email);
        }
    }

    /**
     * @param \Spryker\Zed\MerchantUserGui\Communication\Form\Constraint\UniqueEmailConstraint $constraint
     * @param string $email
     *
     * @return void
     */
    protected function addViolation(Constraint $constraint, string $email): void
    {
        $this->context->buildViolation($constraint->getMessage())
            ->setParameter('{{ username }}', $email)
            ->addViolation();
    }
}

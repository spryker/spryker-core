<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailConstraintValidator extends ConstraintValidator
{
    /**
     * @param string $email
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\MerchantUserGui\Communication\Form\Constraint\UniqueEmailConstraint $constraint
     *
     * @return void
     */
    public function validate($email, Constraint $constraint): void
    {
        if (!$constraint->getUserFacade()->hasUserByUsername($email)) {
            return;
        }

        $userTransfer = $constraint->getUserFacade()->getUserByUsername($email);
        /** @var \Generated\Shared\Transfer\UserTransfer $formDataUserTransfer */
        $formDataUserTransfer = $this->context->getRoot()->getData();

        if ($userTransfer->getIdUser() !== $formDataUserTransfer->getIdUser()) {
            $this->context->addViolation('User with email "{{ username }}" already exists.', [
                '{{ username }}' => $email,
            ]);
        }
    }
}

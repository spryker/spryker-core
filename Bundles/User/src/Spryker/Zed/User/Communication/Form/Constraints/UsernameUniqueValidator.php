<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Form\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UsernameUniqueValidator extends ConstraintValidator
{

    /**
     * @param string $username
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\User\Communication\Form\Constraints\UsernameUnique $constraint
     *
     * @return void
     */
    public function validate($username, Constraint $constraint)
    {
        if (!$constraint instanceof UsernameUnique) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UsernameUnique');
        }

        if (!$this->isUsernameValid($username, $constraint)) {
            $this->buildViolation($constraint->getMessage())
                ->setParameter('{{ username }}', $this->formatValue($username))
                ->addViolation();
        }
    }

    /**
     * @param string $username
     * @param \Spryker\Zed\User\Communication\Form\Constraints\UsernameUnique $constraint
     *
     * @return bool
     */
    protected function isUsernameValid($username, UsernameUnique $constraint)
    {
        if ($constraint->getUsername() !== $username) {
            return !$constraint->getFacadeUser()->hasUserByUsername($username);
        }

        return true;
    }

}

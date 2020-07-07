<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigation\Business\Validator\Constraint;

use Generated\Shared\Transfer\NavigationCriteriaTransfer;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NavigationKeyExistsConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof NavigationKeyExistsConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                NavigationKeyExistsConstraint::class,
                get_class($constraint)
            ));
        }

        $navigationTransfer = (new NavigationCriteriaTransfer())->setKey($value);
        if ($constraint->getNavigationFacade()->findNavigationByCriteria($navigationTransfer) !== null) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->addViolation();
    }
}

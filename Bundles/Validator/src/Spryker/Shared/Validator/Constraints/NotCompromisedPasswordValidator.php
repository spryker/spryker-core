<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Validator\Constraints;

use Spryker\Shared\Validator\ValidatorConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotCompromisedPasswordValidator as SymfonyNotCompromisedPasswordValidator;

class NotCompromisedPasswordValidator extends SymfonyNotCompromisedPasswordValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (in_array(APPLICATION_ENV, ValidatorConfig::getLessStrictEnvironments())) {
            return;
        }

        parent::validate($value, $constraint);
    }
}

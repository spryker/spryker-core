<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Constraints;

use Symfony\Component\Validator\Constraint;

class EmailConstraint extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Email is invalid.';
}

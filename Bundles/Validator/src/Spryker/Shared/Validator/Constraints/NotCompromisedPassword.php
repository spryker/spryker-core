<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Validator\Constraints;

use Symfony\Component\Validator\Constraints\NotCompromisedPassword as SymfonyNotCompromisedPassword;

/**
 * Use this constraint to check if a password has been leaked in a data breach. It excludes a list of environments where validation should be less strict. E.g., compromised password for CI envs can be used for the sake of testing.
 */
class NotCompromisedPassword extends SymfonyNotCompromisedPassword
{
}

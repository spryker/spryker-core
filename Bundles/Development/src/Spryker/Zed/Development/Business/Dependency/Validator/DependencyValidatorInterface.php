<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator;

use Generated\Shared\Transfer\DependencyValidationRequestTransfer;
use Generated\Shared\Transfer\DependencyValidationResponseTransfer;

interface DependencyValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DependencyValidationRequestTransfer $dependencyValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyValidationResponseTransfer
     */
    public function validate(DependencyValidationRequestTransfer $dependencyValidationRequestTransfer): DependencyValidationResponseTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Filter\Validator;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

interface DynamicEntityPreValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param callable $filterCallback
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function validate(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        callable $filterCallback,
        string $errorPath
    ): ?ErrorTransfer;
}

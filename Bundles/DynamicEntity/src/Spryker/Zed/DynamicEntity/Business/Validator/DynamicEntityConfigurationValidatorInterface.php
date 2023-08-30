<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator;

use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;

interface DynamicEntityConfigurationValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function validate(
        DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer;
}

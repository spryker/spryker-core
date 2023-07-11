<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Mapper;

use Exception;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

interface ExceptionToErrorMapperInterface
{
    /**
     * @param \Exception $exception
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function map(Exception $exception, DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): ?ErrorTransfer;
}

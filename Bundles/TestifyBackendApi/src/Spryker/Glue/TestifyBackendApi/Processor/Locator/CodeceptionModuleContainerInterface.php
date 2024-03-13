<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Locator;

use Codeception\Lib\ModuleContainer;

interface CodeceptionModuleContainerInterface
{
    /**
     * @return \Codeception\Lib\ModuleContainer
     */
    public function initModuleContainer(): ModuleContainer;
}

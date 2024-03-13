<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Resolver;

use Codeception\Lib\ModuleContainer;
use Generated\Shared\Transfer\DynamicFixtureOperationTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface OperationStrategyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicFixtureOperationTransfer $dynamicFixtureOperationTransfer
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer>|null
     */
    public function execute(
        DynamicFixtureOperationTransfer $dynamicFixtureOperationTransfer,
        ModuleContainer $moduleContainer
    ): AbstractTransfer|\ArrayObject|null;
}

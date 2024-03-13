<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Strategy;

use ArrayObject;
use Codeception\Lib\ModuleContainer;
use Codeception\Step\Action;
use Generated\Shared\Transfer\DynamicFixtureOperationTransfer;
use Spryker\Glue\TestifyBackendApi\TestifyBackendApiConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class HelperOperationStrategy implements OperationStrategyInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return TestifyBackendApiConfig::OPERATION_TYPE_HELPER;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicFixtureOperationTransfer $dynamicFixtureOperationTransfer
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array<string, mixed> $resolvedArguments
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer>|null
     */
    public function execute(
        DynamicFixtureOperationTransfer $dynamicFixtureOperationTransfer,
        ModuleContainer $moduleContainer,
        array $resolvedArguments
    ): AbstractTransfer|ArrayObject|null {
        $codeceptionAction = new Action($dynamicFixtureOperationTransfer->getNameOrFail(), $resolvedArguments);
        $helperOutput = $codeceptionAction->run($moduleContainer);

        return $helperOutput instanceof AbstractTransfer ? $helperOutput : null;
    }
}

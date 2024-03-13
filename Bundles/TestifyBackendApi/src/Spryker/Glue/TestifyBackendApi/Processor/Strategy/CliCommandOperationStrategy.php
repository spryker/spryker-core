<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Strategy;

use ArrayObject;
use Codeception\Lib\ModuleContainer;
use Generated\Shared\Transfer\CliCommandDynamicFixturesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\DynamicFixtureOperationTransfer;
use Spryker\Glue\TestifyBackendApi\TestifyBackendApiConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\Process\Process;
use Throwable;

class CliCommandOperationStrategy implements OperationStrategyInterface
{
    use LoggerTrait;

    /**
     * @uses \Spryker\Zed\Kernel\Communication\Console\Console::CODE_SUCCESS
     *
     * @var int
     */
    protected const CODE_SUCCESS = 0;

    /**
     * @uses \Spryker\Zed\Kernel\Communication\Console\Console::CODE_ERROR
     *
     * @var int
     */
    protected const CODE_ERROR = 1;

    /**
     * @return string
     */
    public function getType(): string
    {
        return TestifyBackendApiConfig::OPERATION_TYPE_CLI_COMMAND;
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
        $process = new Process(explode(' ', $dynamicFixtureOperationTransfer->getNameOrFail()), APPLICATION_ROOT_DIR);
        $process->setTimeout(null);

        try {
            $process->run();
        } catch (Throwable $exception) {
            $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);
        }

        return (new CliCommandDynamicFixturesBackendApiAttributesTransfer())
            ->setOutput($process->getOutput())
            ->setStatusCode($process->isSuccessful() ? static::CODE_SUCCESS : static::CODE_ERROR);
    }
}

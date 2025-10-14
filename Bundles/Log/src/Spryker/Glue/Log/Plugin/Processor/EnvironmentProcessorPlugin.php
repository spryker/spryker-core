<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log\Plugin\Processor;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface;
use Throwable;

/**
 * @method \Spryker\Glue\Log\LogFactory getFactory()
 */
class EnvironmentProcessorPlugin extends AbstractPlugin implements LogProcessorPluginInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @return array
     */
    public function __invoke(array $data): array
    {
        try {
            return $this->getFactory()->createEnvironmentProcessor()->__invoke($data);
        } catch (Throwable $exception) {
            // If the environment processor fails (e.g. Redis / Valkey is empty or a connection cannot be established), return the original data without environment context.
            return $data;
        }
    }
}

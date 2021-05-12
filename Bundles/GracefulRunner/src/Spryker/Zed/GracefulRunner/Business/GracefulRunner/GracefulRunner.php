<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GracefulRunner\Business\GracefulRunner;

use Generator;
use Seld\Signal\SignalHandler;
use Spryker\Zed\GracefulRunner\GracefulRunnerConfig;

class GracefulRunner implements GracefulRunnerInterface
{
    /**
     * @var \Spryker\Zed\GracefulRunner\GracefulRunnerConfig
     */
    protected $config;

    /**
     * @var int
     */
    protected $executedIterations = 0;

    /**
     * @param \Spryker\Zed\GracefulRunner\GracefulRunnerConfig $config
     */
    public function __construct(GracefulRunnerConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generator $generator
     * @param string|null $throwableClassName
     *
     * @return int
     */
    public function run(Generator $generator, ?string $throwableClassName = null): int
    {
        $signalHandler = $this->createSignalHandler();

        while ($generator->valid()) {
            $generator->next();

            $this->executedIterations++;

            if ($signalHandler->isTriggered()) {
                if ($throwableClassName) {
                    $generator->throw(new $throwableClassName(
                        'Signal was received, stopped Generator execution. Wrap your Generator into try/catch to continue script execution.'
                    ));
                }

                break;
            }
        }

        $signalHandler->unregister($this->config->getSignalsToAddHandlerTo());

        return $this->executedIterations;
    }

    /**
     * @return \Seld\Signal\SignalHandler
     */
    protected function createSignalHandler(): SignalHandler
    {
        return SignalHandler::create($this->config->getSignalsToAddHandlerTo());
    }
}

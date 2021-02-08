<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GracefulRunner\Business\GracefulRunner;

use Generator;
use Seld\Signal\SignalHandler;

class GracefulRunner implements GracefulRunnerInterface
{
    /**
     * @var int
     */
    protected $executedIterations = 0;

    /**
     * @param \Generator $generator
     *
     * @return int
     */
    public function run(Generator $generator): int
    {
        $signalHandler = SignalHandler::create();

        while ($generator->valid()) {
            $generator->next();

            $this->executedIterations++;

            if ($signalHandler->isTriggered()) {
                break;
            }
        }

        return $this->executedIterations;
    }
}

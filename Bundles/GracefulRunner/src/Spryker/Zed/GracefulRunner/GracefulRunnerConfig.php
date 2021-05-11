<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GracefulRunner;

use Seld\Signal\SignalHandler;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class GracefulRunnerConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns signals a new signal handler should be attached to.
     *
     * @api
     *
     * @return string[]
     */
    public function getSignalsToAddHandlerTo(): array
    {
        return [
            SignalHandler::SIGINT,
            SignalHandler::SIGTERM,
        ];
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Dependency\Plugin;

use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\ProcessableHandlerInterface;
use Monolog\Logger;

// phpcs:disable
if (Logger::API === 1) {
    interface LogHandlerPluginInterface extends HandlerInterface
    {
    }
} else {
    interface LogHandlerPluginInterface extends HandlerInterface, ProcessableHandlerInterface, FormattableHandlerInterface
    {
    }
}
// phpcs:enable

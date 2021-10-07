<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Config;

interface LoggerConfigInterface
{
    /**
     * @return string
     */
    public function getChannelName();

    /**
     * @return array<\Monolog\Handler\HandlerInterface>
     */
    public function getHandlers();

    /**
     * @return array<callable>
     */
    public function getProcessors();
}

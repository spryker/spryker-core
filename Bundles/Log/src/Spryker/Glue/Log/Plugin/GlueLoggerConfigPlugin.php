<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log\Plugin;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

/**
 * @method \Spryker\Glue\Log\LogFactory getFactory()
 * @method \Spryker\Glue\Log\LogConfig getConfig()
 */
class GlueLoggerConfigPlugin extends AbstractPlugin implements LoggerConfigInterface
{
    /**
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->getConfig()->getChannelName();
    }

    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->getFactory()->getHandlers();
    }

    /**
     * @return callable[]
     */
    public function getProcessors(): array
    {
        return $this->getFactory()->getProcessors();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log\Plugin;

use Spryker\Shared\Log\Config\LoggerConfigInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Log\LogFactory getFactory()
 * @method \Spryker\Yves\Log\LogConfig getConfig()
 */
class YvesLoggerConfigPlugin extends AbstractPlugin implements LoggerConfigInterface
{
    /**
     * @return string
     */
    public function getChannelName()
    {
        return $this->getConfig()->getChannelName();
    }

    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->getFactory()->getHandlers();
    }

    /**
     * @return callable[]
     */
    public function getProcessors()
    {
        return $this->getFactory()->getProcessors();
    }
}

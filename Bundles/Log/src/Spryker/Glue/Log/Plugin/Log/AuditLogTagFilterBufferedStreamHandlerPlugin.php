<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log\Plugin\Log;

use Monolog\Handler\HandlerInterface;
use Spryker\Glue\Log\Plugin\Handler\AbstractHandlerPlugin;

/**
 * @method \Spryker\Glue\Log\LogFactory getFactory()
 * @method \Spryker\Glue\Log\LogConfig getConfig()
 */
class AuditLogTagFilterBufferedStreamHandlerPlugin extends AbstractHandlerPlugin
{
    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function getHandler(): HandlerInterface
    {
        if (!$this->handler) {
            $this->handler = $this->getFactory()
                ->createTagFilterBufferedStreamHandler($this->getConfig()->getAuditLogTagDisallowList());
        }

        return $this->handler;
    }
}

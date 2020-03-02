<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublisherExtension\Dependency\Plugin;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

interface PublisherPluginInterface extends EventBulkHandlerInterface
{
    /**
     * Specification:
     * - Registers an array of events that should be handled.
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array;
}

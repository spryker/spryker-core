<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency\Plugin;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface EventHandlerInterface extends EventBaseHandlerInterface
{
    /**
     * Specification:
     *  - Listeners needs to implement this interface to execute the codes for each
     *  event.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $transfer, $eventName);
}

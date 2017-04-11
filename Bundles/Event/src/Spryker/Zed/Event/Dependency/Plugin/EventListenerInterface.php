<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency\Plugin;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface EventListenerInterface
{

    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function handle(TransferInterface $eventTransfer);

}

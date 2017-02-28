<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method QueueBusinessFactory getFactory()
 */
class QueueFacade extends AbstractFacade implements QueueFacadeInterface
{

    /**
     * @return void
     */
    public function runQueueReceiverTask()
    {
        $this->getFactory()->createTask()->run();
    }
}

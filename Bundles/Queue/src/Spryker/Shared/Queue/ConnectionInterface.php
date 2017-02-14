<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

use Generated\Shared\Transfer\QueueOptionTransfer;

interface ConnectionInterface
{

    /**
     * @return bool
     */
    public function open();

    /**
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return mixed
     */
    public function createQueue(QueueOptionTransfer $queueOptionTransfer);

    /**
     * @return bool
     */
    public function close();
}

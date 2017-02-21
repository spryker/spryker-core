<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
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
     * @param \Generated\Shared\Transfer\QueueOptionTransfer $queueOptionTransfer
     *
     * @return mixed
     */
    public function createQueue(QueueOptionTransfer $queueOptionTransfer);

    /**
     * @return bool
     */
    public function close();

}

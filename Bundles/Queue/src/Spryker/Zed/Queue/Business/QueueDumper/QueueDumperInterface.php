<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\QueueDumper;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueDumpResponseTransfer;

interface QueueDumperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueueDumpRequestTransfer $queueDumpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QueueDumpResponseTransfer
     */
    public function dumpQueue(QueueDumpRequestTransfer $queueDumpRequestTransfer): QueueDumpResponseTransfer;
}

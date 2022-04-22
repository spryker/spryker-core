<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Worker;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;

interface WorkerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return void
     */
    public function runWorker(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): void;
}

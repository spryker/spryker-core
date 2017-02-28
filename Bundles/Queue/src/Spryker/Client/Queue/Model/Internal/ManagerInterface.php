<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Internal;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Generated\Shared\Transfer\QueueOptionTransfer;

interface ManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\QueueOptionTransfer $queueOptionTransfer
     *
     * @return QueueOptionTransfer
     */
    public function createQueue(QueueOptionTransfer $queueOptionTransfer);

    /**
     * @param string $queueName
     *
     * @return bool
     */
    public function deleteQueue($queueName);

    /**
     * @param string $queueName
     *
     * @return bool
     */
    public function purgeQueue($queueName);

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return QueueMessageTransfer
     */
    public function handleErrorMessage(QueueMessageTransfer $queueMessageTransfer);

}

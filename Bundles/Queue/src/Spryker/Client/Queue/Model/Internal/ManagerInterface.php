<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Internal;

interface ManagerInterface
{
    /**
     * Specification
     *  - Creates a queue with the given queue name and returns the queue information
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return array
     */
    public function createQueue($queueName, array $options = []);

    /**
     * Specification
     *  - Purges all messages from the queue
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function purgeQueue($queueName, array $options = []);

    /**
     * Specification
     *  - Deletes a queue with the given queue name
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function deleteQueue($queueName, array $options = []);
}

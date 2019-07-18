<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Synchronization;

interface SynchronizationInterface
{
    /**
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function write(array $data, $queueName);

    /**
     * @param array $data
     *
     * @return void
     */
    public function writeBulk(array $data): void;

    /**
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function delete(array $data, $queueName);

    /**
     * @param array $data
     *
     * @return void
     */
    public function deleteBulk(array $data): void;
}

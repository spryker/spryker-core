<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Synchronization;

interface SynchronizationInterface
{
    /**
     * @param array<string, mixed> $data
     * @param string $queueName
     *
     * @return void
     */
    public function write(array $data, $queueName);

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function writeBulk(array $data): void;

    /**
     * @param array<string, mixed> $data
     * @param string $queueName
     *
     * @return void
     */
    public function delete(array $data, $queueName);

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function deleteBulk(array $data): void;

    /**
     * @param string $destinationType
     *
     * @return bool
     */
    public function isDestinationTypeApplicable(string $destinationType): bool;
}

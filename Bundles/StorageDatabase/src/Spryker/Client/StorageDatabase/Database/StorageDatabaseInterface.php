<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Database;

interface StorageDatabaseInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array;

    /**
     * @return void
     */
    public function resetAccessStats(): void;

    /**
     * @return array
     */
    public function getAccessStats(): array;

    /**
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug);
}

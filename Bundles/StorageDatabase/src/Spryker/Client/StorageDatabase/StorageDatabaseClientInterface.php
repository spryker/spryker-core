<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

interface StorageDatabaseClientInterface
{
    /**
     * Specification:
     *  - Gets the value by key from storage database.
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * Specification:
     *  - Gets multiple values by array of keys from storage database.
     *
     * @api
     *
     * @param string[] $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array;

    /**
     * Specification:
     *  - Resets in-memory access statistics for storage database.
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats(): void;

    /**
     * Specification:
     *  - Gets in-memory access statistics for storage database.
     *
     * @api
     *
     * @return array
     */
    public function getAccessStats(): array;

    /**
     * Specification:
     *  - Sets debug mode.
     *
     * @api
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void;
}

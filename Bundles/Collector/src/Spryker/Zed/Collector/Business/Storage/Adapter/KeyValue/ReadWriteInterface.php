<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue;

interface ReadWriteInterface extends ReadInterface
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function set($key, $value);

    /**
     * @param array<string, mixed> $items
     * @param string $prefix
     *
     * @return mixed|bool
     */
    public function setMulti(array $items, $prefix = RedisRead::KV_PREFIX);

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return int
     */
    public function delete($key, $prefix = RedisRead::KV_PREFIX);

    /**
     * @param array<string, mixed> $keys
     * @param string $prefix
     *
     * @return bool
     */
    public function deleteMulti(array $keys, $prefix = RedisRead::KV_PREFIX);

    /**
     * @return int
     */
    public function deleteAll();
}

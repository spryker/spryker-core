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
     * @return void
     */
    public function set($key, $value);

    /**
     * @param array $items
     * @param string $prefix
     *
     * @return bool|mixed
     */
    public function setMulti(array $items, $prefix = RedisRead::KV_PREFIX);

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return bool|mixed
     */
    public function delete($key, $prefix = RedisRead::KV_PREFIX);

    /**
     * @param array $keys
     * @param string $prefix
     *
     * @return bool|mixed
     */
    public function deleteMulti(array $keys, $prefix = RedisRead::KV_PREFIX);

    /**
     * @return mixed
     */
    public function deleteAll();
}

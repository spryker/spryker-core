<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Redis;

interface SessionRedisWrapperInterface
{
    /**
     * @param mixed $key
     *
     * @return string
     */
    public function get($key);

    /**
     * @param mixed $key
     * @param mixed $seconds
     * @param mixed $value
     *
     * @return int
     */
    public function setex($key, $seconds, $value);

    /**
     * @param mixed $key
     * @param mixed $value
     * @param mixed|null $expireResolution
     * @param mixed|null $expireTTL
     * @param mixed|null $flag
     *
     * @return mixed
     */
    public function set($key, $value, $expireResolution = null, $expireTTL = null, $flag = null);

    /**
     * @param array $keys
     *
     * @return int
     */
    public function del($keys);

    /**
     * @param mixed $script
     * @param mixed $numkeys
     * @param mixed|null $keyOrArg1
     * @param mixed|null $keyOrArgN
     *
     * @return mixed
     */
    public function eval($script, $numkeys, $keyOrArg1 = null, $keyOrArgN = null);

    /**
     * @return void
     */
    public function connect();

    /**
     * @return void
     */
    public function disconnect();

    /**
     * @return bool
     */
    public function isConnected();

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget($keys);

    /**
     * @param array $dictionary
     *
     * @return mixed
     */
    public function mset($dictionary);

    /**
     * @param mixed|null $section
     *
     * @return array
     */
    public function info($section = null);

    /**
     * @param mixed $pattern
     *
     * @return array
     */
    public function keys($pattern);
}

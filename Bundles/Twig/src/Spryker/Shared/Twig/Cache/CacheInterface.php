<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Cache;

interface CacheInterface
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * @param string $key
     *
     * @return string
     */
    public function get($key);

    /**
     * @param string $key
     * @param string|bool $value
     *
     * @return void
     */
    public function set($key, $value);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key);
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Dependency\Client;

interface SprykGuiToSessionClientInterface
{
    /**
     * @param string $name
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return mixed
     */
    public function set(string $name, $value);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;
}

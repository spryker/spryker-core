<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Dependency\Client;

interface SecurityGuiToSessionClientInterface
{
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
     * @return mixed
     */
    public function remove(string $name);
}

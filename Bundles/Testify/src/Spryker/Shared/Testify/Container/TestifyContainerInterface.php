<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify\Container;

interface TestifyContainerInterface
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value);
}

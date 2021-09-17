<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify\Config;

interface TestifyConfigInterface
{
    /**
     * @param string $key
     * @param array|string|float|int|bool $value
     *
     * @return void
     */
    public function set($key, $value);
}

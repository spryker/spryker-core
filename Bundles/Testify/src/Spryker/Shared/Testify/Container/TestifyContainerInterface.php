<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify\Container;

interface TestifyContainerInterface
{
    /**
     * @param string $id
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $id, $value): void;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

interface LastConnectionProviderInterface
{
    /**
     * @return \SprykerTest\Glue\Testify\Helper\Connection|null
     */
    public function getLastConnection(): ?Connection;
}

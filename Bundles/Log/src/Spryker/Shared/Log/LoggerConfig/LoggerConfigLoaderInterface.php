<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\LoggerConfig;

interface LoggerConfigLoaderInterface
{
    /**
     * @return bool
     */
    public function accept();

    /**
     * @return \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    public function create();
}

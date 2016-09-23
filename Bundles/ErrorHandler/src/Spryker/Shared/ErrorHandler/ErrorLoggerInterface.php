<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use Exception;

interface ErrorLoggerInterface
{

    /**
     * @param \Exception $exception
     *
     * @return void
     */
    public function log(Exception $exception);

}

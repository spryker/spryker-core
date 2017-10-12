<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

interface ErrorLoggerInterface
{
    /**
     * @param \Exception|\Throwable $exception
     *
     * @return void
     */
    public function log($exception);
}

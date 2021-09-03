<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler\ErrorRenderer;

class ApiErrorRenderer implements ErrorRendererInterface
{
    protected const DEFAULT_ERROR_MESSAGE = 'Something went wrong!';

    /**
     * @param \Throwable $exception
     *
     * @return string
     */
    public function render($exception)
    {
        return static::DEFAULT_ERROR_MESSAGE;
    }
}

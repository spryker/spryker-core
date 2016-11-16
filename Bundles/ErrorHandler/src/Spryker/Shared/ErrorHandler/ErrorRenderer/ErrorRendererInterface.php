<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler\ErrorRenderer;

interface ErrorRendererInterface
{

    /**
     * @param \Exception|\Throwable $exception
     *
     * @return string
     */
    public function render($exception);

}

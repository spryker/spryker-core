<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler\ErrorRenderer;

use Exception;

interface ErrorRendererInterface
{

    /**
     * @param \Exception $exception
     *
     * @return string
     */
    public function render(Exception $exception);

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

class RouterController extends AbstractController
{
    /**
     * @return string
     */
    public function indexAction(): string
    {
        return static::class;
    }
}

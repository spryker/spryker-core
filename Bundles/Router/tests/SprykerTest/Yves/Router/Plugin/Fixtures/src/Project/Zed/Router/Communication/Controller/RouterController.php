<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

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

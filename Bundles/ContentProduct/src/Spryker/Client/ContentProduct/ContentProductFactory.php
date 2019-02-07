<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

use Spryker\Client\ContentProduct\Executor\AbstractProductListTermExecutor;
use Spryker\Client\ContentProduct\Executor\AbstractProductListTermExecutorInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ContentProductFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentProduct\Executor\AbstractProductListTermExecutorInterface
     */
    public function createAbstractProductListTermExecutor(): AbstractProductListTermExecutorInterface
    {
        return new AbstractProductListTermExecutor();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProductConnector;

use Spryker\Client\ContentProductConnector\Executor\AbstractProductListTermExecutor;
use Spryker\Client\Kernel\AbstractFactory;

class ContentProductConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentProductConnector\Executor\ContentTermExecutorInterface
     */
    public function createAbstractProductListTermExecutor()
    {
        return new AbstractProductListTermExecutor();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel\Fixtures\Plugin;

use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Kernel\AbstractServiceFactory;

class FooPlugin extends AbstractPlugin
{
    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    public function getFactory(): AbstractServiceFactory
    {
        return parent::getFactory();
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function getService(): AbstractService
    {
        return parent::getService();
    }
}

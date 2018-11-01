<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel\Fixtures\Plugin;

use Spryker\Service\Kernel\AbstractPlugin;

class FooPlugin extends AbstractPlugin
{
    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    public function getFactory()
    {
        return parent::getFactory();
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function getService()
    {
        return parent::getService();
    }
}

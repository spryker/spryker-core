<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Kernel\Fixtures\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;

class FooPlugin extends AbstractPlugin
{
    /**
     * @return \Spryker\Client\Kernel\AbstractPlugin
     */
    public function getFactory()
    {
        return parent::getFactory();
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    public function getClient()
    {
        return parent::getClient();
    }
}

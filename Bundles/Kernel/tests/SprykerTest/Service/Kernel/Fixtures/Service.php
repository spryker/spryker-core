<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel\Fixtures;

use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Kernel\AbstractServiceFactory;

class Service extends AbstractService
{
    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    public function getFactory(): AbstractServiceFactory
    {
        return parent::getFactory();
    }
}

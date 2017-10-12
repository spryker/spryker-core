<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel\Fixtures;

use Spryker\Service\Kernel\AbstractService;

class Service extends AbstractService
{
    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    public function getFactory()
    {
        return parent::getFactory();
    }
}

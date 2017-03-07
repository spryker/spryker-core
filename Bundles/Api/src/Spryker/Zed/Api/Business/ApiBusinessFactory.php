<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Spryker\Zed\Api\ApiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainer getQueryContainer()
 */
class ApiBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Api\Dependency\Facade\ApiToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::FACADE_USER);
    }

}

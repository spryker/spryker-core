<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Spryker\Zed\FactFinder\Business\Api\ApiFacade;
use Spryker\Zed\FactFinder\Business\Api\FFConnector;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class FactFinderBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\FactFinder\Business\Api\FFConnector
     */
    public function createFFConnector()
    {
        return new FFConnector($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\FactFinder\Business\Api\ApiFacade
     */
    public function createApiFacade()
    {
        return new ApiFacade($this->createFFConnector());
    }

}

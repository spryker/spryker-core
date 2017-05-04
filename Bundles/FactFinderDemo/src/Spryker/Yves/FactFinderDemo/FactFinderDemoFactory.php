<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderDemo;

use Spryker\Yves\Kernel\AbstractFactory;

class FactFinderDemoFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\FactFinder\FactFinderClient
     */
    public function getFactFinderClient()
    {
        return $this->getProvidedDependency(FactFinderDemoDependencyProvider::FACT_FINDER_CLIENT);
    }

}

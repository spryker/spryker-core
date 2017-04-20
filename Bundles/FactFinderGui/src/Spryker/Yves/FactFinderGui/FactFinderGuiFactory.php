<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui;

use Spryker\Client\FactFinder\FactFinderClient;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\FactFinder\FactFinderClientInterface getClient()
 */
class FactFinderGuiFactory extends AbstractFactory
{

    /**
     * @return FactFinderClient
     */
    public function getFactFinderClient()
    {
        return $this->getProvidedDependency(FactFinderGuiDependencyProvider::FACT_FINDER_CLIENT);
    }

    /**
     * @return string
     */
    public function getExportFolderPath()
    {
        return APPLICATION_ROOT_DIR . '/public/fact_finder/';
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Spryker\Shared\FactFinder\FactFinderConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class FactFinderConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->get(FactFinderConstants::ENVIRONMENT);
    }

    /**
     * @return array
     */
    public function getFactFinderConfiguration()
    {
        return $this->get(FactFinderConstants::ENVIRONMENT . $this->getEnvironment());
    }

    /**
     * @return string
     */
    public function getCsvDirectory()
    {
        return $this->get(FactFinderConstants::CSV_DIRECTORY);
    }

    /**
     * @return string
     */
    public function getLog4PhpConfigPath()
    {
        return $this->get(FactFinderConstants::PHP_LOGGER_CONFIG_PATH);
    }

}

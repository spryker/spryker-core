<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilNumber\Communication;

use Spryker\Service\UtilNumber\UtilNumberServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\UtilNumber\UtilNumberDependencyProvider;

/**
 * @method \Spryker\Zed\UtilNumber\UtilNumberConfig getConfig()
 */
class UtilNumberCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Service\UtilNumber\UtilNumberServiceInterface
     */
    public function getUtilNumberService(): UtilNumberServiceInterface
    {
        return $this->getProvidedDependency(UtilNumberDependencyProvider::SERVICE_UTIL_NUMBER);
    }
}

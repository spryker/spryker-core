<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication;

use Spryker\Zed\Api\Business\Model\Processor\Provider\PreProcessorProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainer getQueryContainer()
 */
class ApiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Provider\PreProcessorProviderInterface
     */
    public function createPreProcessorProvider()
    {
        return new PreProcessorProvider();
    }

}

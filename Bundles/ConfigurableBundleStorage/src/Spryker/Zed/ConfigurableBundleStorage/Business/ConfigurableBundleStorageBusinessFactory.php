<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business;

use Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleStoragePublisher;
use Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleStoragePublisherInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface getEntityManager()
 */
class ConfigurableBundleStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleStoragePublisherInterface
     */
    public function createConfigurableBundleStoragePublisher(): ConfigurableBundleStoragePublisherInterface
    {
        return new ConfigurableBundleStoragePublisher(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getConfig()
        );
    }
}

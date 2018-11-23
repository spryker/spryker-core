<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Communication;

use Spryker\Zed\Collector\CollectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Collector\CollectorConfig getConfig()
 * @method \Spryker\Zed\Collector\Business\CollectorFacadeInterface getFacade()
 */
class CollectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::FACADE_LOCALE);
    }
}

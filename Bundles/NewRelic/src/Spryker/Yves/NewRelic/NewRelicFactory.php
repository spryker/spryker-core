<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\NewRelic;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\NewRelic\Plugin\ControllerListener;

/**
 * @method \Spryker\Yves\NewRelic\NewRelicConfig getConfig()
 */
class NewRelicFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\NewRelic\Plugin\ControllerListener
     */
    public function createControllerListener()
    {
        return new ControllerListener(
            $this->getNewRelicApi(),
            $this->getSystem(),
            $this->getConfig()->getIgnorableTransactionRouteNames()
        );
    }

    /**
     * @return \Spryker\Shared\NewRelicApi\NewRelicApiInterface
     */
    protected function getNewRelicApi()
    {
        return $this->getProvidedDependency(NewRelicDependencyProvider::NEW_RELIC_API);
    }

    /**
     * @return \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface
     */
    protected function getSystem()
    {
        return $this->getProvidedDependency(NewRelicDependencyProvider::SERVICE_NETWORK);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\NewRelic\Communication\Plugin\ControllerListener;
use Spryker\Zed\NewRelic\Communication\Plugin\GatewayControllerListener;
use Spryker\Zed\NewRelic\NewRelicDependencyProvider;

/**
 * @method \Spryker\Zed\NewRelic\NewRelicConfig getConfig()
 */
class NewRelicCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\NewRelic\Communication\Plugin\GatewayControllerListener
     */
    public function createGatewayControllerListener()
    {
        return new GatewayControllerListener(
            $this->getNewRelicApi()
        );
    }

    /**
     * @return \Spryker\Zed\NewRelic\Communication\Plugin\ControllerListener
     */
    public function createControllerListener()
    {
        return new ControllerListener(
            $this->getNewRelicApi(),
            $this->getStore(),
            $this->getUtilNetworkService(),
            $this->getConfig()->getIgnorableTransactions()
        );
    }

    /**
     * @return \Spryker\Shared\NewRelicApi\NewRelicApiInterface
     */
    public function getNewRelicApi()
    {
        return $this->getProvidedDependency(NewRelicDependencyProvider::NEW_RELIC_API);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(NewRelicDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface
     */
    public function getUtilNetworkService()
    {
        return $this->getProvidedDependency(NewRelicDependencyProvider::SERVICE_NETWORK);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;

/**
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 */
class StateMachineCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface[]
     */
    public function getStateMachineHandlerPlugins()
    {
        return $this->getProvidedDependency(StateMachineDependencyProvider::PLUGINS_STATE_MACHINE_HANDLERS);
    }

    /**
     * @return \Spryker\Zed\StateMachine\StateMachineConfig
     */
    public function getBundleConfig()
    {
        return $this->getConfig();
    }
}

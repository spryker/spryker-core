<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventItemTriggerFormDataProvider;
use Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventTriggerFormDataProvider;
use Spryker\Zed\StateMachine\Communication\Form\EventItemTriggerForm;
use Spryker\Zed\StateMachine\Communication\Form\EventTriggerForm;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineRepositoryInterface getRepository()
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

    /**
     * @return \Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventTriggerFormDataProvider
     */
    public function createEventTriggerFormDataProvider(): EventTriggerFormDataProvider
    {
        return new EventTriggerFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventItemTriggerFormDataProvider
     */
    public function createEventItemTriggerFormDataProvider(): EventItemTriggerFormDataProvider
    {
        return new EventItemTriggerFormDataProvider();
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEventTriggerForm(array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(EventTriggerForm::class, null, $options);
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEventItemTriggerForm(array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(EventItemTriggerForm::class, null, $options);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Factory;

use Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventItemTriggerFormDataProvider;
use Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventTriggerFormDataProvider;
use Spryker\Zed\StateMachine\Communication\Form\EventItemTriggerForm;
use Spryker\Zed\StateMachine\Communication\Form\EventTriggerForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class StateMachineTriggerFormFactory implements StateMachineTriggerFormFactoryInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
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
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEventTriggerForm(): FormInterface
    {
        return $this->formFactory->create(EventTriggerForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEventItemTriggerForm(): FormInterface
    {
        return $this->formFactory->create(EventItemTriggerForm::class);
    }

    /**
     * @param int $identifier
     * @param string $redirect
     * @param string $stateMachineName
     * @param string $processName
     * @param string $eventName
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFullFilledEventItemTriggerForm(
        int $identifier,
        string $redirect,
        string $stateMachineName,
        string $processName,
        string $eventName
    ): FormInterface {
        $options = $this->createEventItemTriggerFormDataProvider()
            ->getOptions($eventName, $identifier, $redirect, $stateMachineName, $processName);

        return $this->formFactory->create(EventItemTriggerForm::class, null, $options);
    }

    /**
     * @param int $identifier
     * @param string $redirect
     * @param int $idState
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFullFilledEventTriggerForm(
        int $identifier,
        string $redirect,
        int $idState,
        string $event
    ): FormInterface {
        $options = $this->createEventTriggerFormDataProvider()
            ->getOptions($identifier, $redirect, $idState, $event);

        return $this->formFactory->create(EventTriggerForm::class, null, $options);
    }
}
